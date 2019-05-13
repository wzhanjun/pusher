<?php

namespace Wzhanjun\Push\Gateways\Igetui;

use Wzhanjun\Igetui\Sdk\Igetui\IGtAppMessage;
use Wzhanjun\Igetui\Sdk\Igetui\IGtListMessage;
use Wzhanjun\Igetui\Sdk\Igetui\Utils\AppConditions;
use Wzhanjun\Igetui\Sdk\IGtPush;
use Wzhanjun\Igetui\Sdk\Payload\VOIPPayload;
use Wzhanjun\Push\Support\Config;
use Wzhanjun\Push\Traits\HasHttpRequest;
use Wzhanjun\Igetui\Sdk\Igetui\IGtTarget;
use Wzhanjun\Igetui\Sdk\RequestException;
use Wzhanjun\Igetui\Sdk\Igetui\IGtAPNPayload;
use Wzhanjun\Igetui\Sdk\Igetui\IGtSingleMessage;
use Wzhanjun\Igetui\Sdk\Igetui\DictionaryAlertMsg;

use Wzhanjun\Push\Contracts\TargetInterface as Target;
use Wzhanjun\Push\Contracts\MessageInterface as Message;
use Wzhanjun\Igetui\Sdk\Igetui\Template\IGtLinkTemplate;

use Wzhanjun\Igetui\Sdk\Igetui\Template\IGtNotificationTemplate;
use Wzhanjun\Igetui\Sdk\Igetui\Template\IGtNotyPopLoadTemplate;
use Wzhanjun\Igetui\Sdk\Igetui\Template\IGtTransmissionTemplate;


/**
 *  //服务端推送接口，支持三个接口推送
 *  1.PushMessageToSingle 接口：支持对单个用户进行推送
 *  2.PushMessageToList   接口：支持对多个用户进行推送，建议为50个用户
 *  3.pushMessageToApp    接口：对单个应用下的所有用户进行推送，可根据省份，标签，机型过滤推送
 *
 * Class Client
 * @package Wzhanjun\Push\Gateways\IGeTui
 */
class Client
{
    use HasHttpRequest;

    /**
     * 消息模版：
     *
     * 1.NotificationTemplate：通知功能模板
     * 2.NotyPopLoadTemplate：通知弹框下载功能模板
     * 3.LinkTemplate:通知打开链接功能模板
     * 4.TransmissionTemplate:透传功能模板
     */
    const TEMPLATE_NOTIFICATION = 1;
    const TEMPLATE_NOTYPOPLOAD  = 2;
    const TEMPLATE_LINK         = 3;
    const TEMPLATE_TRANSMISSION = 4;

    private $url;

    private $appkey;

    private $appId;

    private $appSecret;

    private $masterSecret;

   public function __construct(Config $config)
   {
        $this->url          = $config->get('url');
        $this->appkey       = $config->get('app_key');
        $this->appId        = $config->get('app_id');
        $this->appSecret    = $config->get('app_secret');
        $this->masterSecret = $config->get('master_secret');
   }

    /**
     * 单发
     *
     * @param Message $message
     * @param Target $target
     * @return mixed|null
     * @throws \Exception
     */
   public function pushMessageToSingle(Message $message, Target $target)
   {
       $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

       // 模板
       $template = $this->getMessageTemplate($message);

       $igtSingleMessage = new IGtSingleMessage();
       $igtSingleMessage->set_data($template);//设置推送消息类型
       $igtSingleMessage->set_isOffline($message->getIsOffline());  //是否离线
       $igtSingleMessage->set_offlineExpireTime($message->getOfflineExpireTime());

       //接收方
       $igtTarget = new IGtTarget();
       $igtTarget->set_appId($this->appId);
       switch ($target->getPushType())
       {
           case Target::PUSH_TYPE_ALIAS:
               $igtTarget->set_alias($target->getAlias());
               break;
           default:
               $igtTarget->set_clientId($target->getDeviceId());
       }

       try {
           $rep = $igt->pushMessageToSingle($igtSingleMessage, $igtTarget);
       } catch (RequestException $e) {
           $requestId = $e->getRequestId();
           $rep = $igt->pushMessageToSingle($igtSingleMessage, $igtTarget, $requestId);
       }

       return $rep;
   }


    /**
     * 推送给列表
     *
     * @param Message $message
     * @param Target $target
     * @return mixed|null
     * @throws RequestException | \Exception
     */
   public function pushMessageToList(Message $message, Target $target)
   {
       $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

       $template = $this->getMessageTemplate($message);

       //个推信息体
       $igtListMessage = new IGtListMessage();
       $igtListMessage->set_isOffline($message->getIsOffline()); //是否离线
       $igtListMessage->set_offlineExpireTime($message->getOfflineExpireTime());
       $igtListMessage->set_data($template); //设置推送消息类型

       $taskName  = $message->getExtra('taskName') ?: null;
       $contentId = $igt->getContentId($igtListMessage, $taskName); //"toList任务别名功能"根据TaskId设置组名，支持下划线，中文，英文，数字

       //接收方
       $targetList = [];

       foreach ($target->getTargetList() as $val)
       {
           $igtTarget = new IGtTarget();

           $igtTarget->set_appId($this->appId);

           if ($target->getPushType() == Target::PUSH_TYPE_ALIAS_LIST)
           {
               $igtTarget->set_alias($val);
           } else {
               $igtTarget->set_clientId($val);
           }

           $targetList[] = $igtTarget;
       }

       return $igt->pushMessageToList($contentId, $targetList);
   }


    /**
     * app群推
     *
     * @param Message $message
     * @param Target $target
     * @return mixed|null
     * @throws \Exception
     */
   public function pushMessageToApp(Message $message, Target $target)
   {
       $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

       // 模板
       $template = $this->getMessageTemplate($message);

       $igtAppMessage = new IGtAppMessage();
       $igtAppMessage->set_data($template);//设置推送消息类型
       $igtAppMessage->set_isOffline($message->getIsOffline()); //是否离线
       $igtAppMessage->set_offlineExpireTime($message->getOfflineExpireTime());
       $igtAppMessage->set_appIdList([$this->appId]);

       //  用户属性
       //  $phoneTypeList = $data['phone_type_list'];//array('ANDROID');
       //  $provinceList = $data['$province_list'];//array('浙江');
       //  $tagList = $data['tag_list'];//array('haha');

       // $age = array("0000", "0010");
       // $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
       // $cdt->addCondition(AppConditions::REGION, $provinceList);
       // $cdt->addCondition(AppConditions::TAG, $tagList);
       // $cdt->addCondition("age", $age);

       $cdt = new AppConditions();

       if (!empty($target->getTags()))
       {
           $cdt->addCondition(AppConditions::TAG, $target->getTags());
       }

       $igtAppMessage->set_conditions($cdt);

       $taskName = $message->getExtra('taskName') ?: null;

       return $igt->pushMessageToApp($igtAppMessage, $taskName);

   }


    /**
     * 绑定别名
     *
     * @param $alias
     * @param $deviceId
     * @return mixed|null
     * @throws RequestException
     */
   public function bindAlias($alias, $deviceId)
   {
       $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

       return $igt->bindAlias($this->appId, $alias, $deviceId);
   }


    /**
     * 查询别名
     *
     * @param $deviceId
     * @return mixed|null
     * @throws RequestException
     */
   public function queryAlias($deviceId)
   {
       $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

       return $igt->queryAlias($this->appId, $deviceId);
   }


    /**
     * 设置标签
     *
     * @param $tagList
     * @param $deviceId
     * @return mixed|null
     * @throws RequestException
     */
   public function setDeviceTags($tagList, $deviceId)
   {
       $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

       return $igt->setClientTag($this->appId, $deviceId, $tagList);
   }

    /**
     * 获取设备标签
     *
     * @param $deviceId
     * @return mixed|null
     * @throws RequestException
     */
   public function getDeviceTags($deviceId)
   {
       $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

       return $igt->getUserTags($this->appId, $deviceId);
   }


    /**
     * 获取设备状态
     *
     * @param $deviceId
     * @return mixed|null
     * @throws RequestException
     */
    public function getClientIdStatus($deviceId)
    {
        $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

        return $igt->getClientIdStatus($this->appId, $deviceId);
    }


    /**
     * 获取推送任务结果
     *
     * @param array $taskList
     * @return mixed|null
     * @throws RequestException
     */
    public function getPushResultByTaskList(array $taskList)
    {
        $igt = new IGtPush($this->url, $this->appkey, $this->masterSecret);

        return $igt->getPushResultByTaskidList($taskList);
    }


    /**
     * 获取推送模板
     *
     * @param Message $message
     * @return IGtLinkTemplate|IGtNotificationTemplate|IGtTransmissionTemplate
     * @throws \Exception
     */
    protected function getMessageTemplate(Message $message)
    {
        switch ($message->getMessageType())
        {
            case Message::MESSAGE_TYPE_NOTICE:
                $template = $this->igtNotificationTemplate($message);
                break;
            case Message::MESSAGE_TYPE_NOTY_POP_LOAD:
                $template = $this->igtNotyPopLoadTemplate($message);
                break;
            case Message::MESSAGE_TYPE_LINK:
                $template = $this->igtLinkTemplate($message);
                break;
            default:
                $template = $this->igtTransmissionTemplate($message);
        }

        return $template;
    }


    /**
     * @param Message $message
     * @return IGtNotificationTemplate
     * @throws \Exception
     */
    protected function igtNotificationTemplate(Message $message)
    {
        $template = new IGtNotificationTemplate();
        $template->set_appId($this->appId);                     // 应用appid
        $template->set_appkey($this->appkey);                   // 应用$this->APPKEY
        $template->set_transmissionType(1);     // 透传消息类型
        $template->set_transmissionContent($message->getTitle() . '');  // 透传内容
        $template->set_title($message->getTitle() . '');   // 通知栏标题
        $template->set_text($message->getBody() . '');     // 通知栏内容
        $template->set_logo($message->getExtra('logo'));   // 通知栏logo
        $template->set_isRing($message->isBell());             // 是否响铃
        $template->set_isVibrate($message->isVibration());     // 是否震动
        $template->set_isClearable($message->isClearAble());   // 通知栏是否可清除
        //$template->set_duration(BEGINTIME,ENDTIME);          // 设置ANDROID客户端在此时间区间内展示消息

        //iOS推送需要设置的pushInfo字段
        $apn = new IGtAPNPayload();
        $alertmsg = new DictionaryAlertMsg();

        $alertmsg->body         = $message->getBody() . '';
        $alertmsg->title        = $message->getTitle() . '';
        $alertmsg->locArgs      = array("locargs");
        $alertmsg->launchImage  = "launchimage";
        $alertmsg->actionLocKey = "ActionLockey";
        $alertmsg->actionLocKey = "启动";
        $apn->alertMsg          = $alertmsg;
        $apn->badge             = 1;
        $apn->category          = "ACTIONABLE";
        $apn->contentAvailable  = 1;
        $apn->customMsg         = array("payload" => $message->getPayload() . '');
        $template->set_apnInfo($apn);

        return $template;
    }


    protected function igtNotyPopLoadTemplate(Message $message)
    {

        $template = new IGtNotyPopLoadTemplate();
        $template->set_appId($this->appId);//应用appid
        $template->set_appkey($this->appkey);//应用appkey

        //通知栏
        $template->set_notyTitle($message->getTitle());//通知栏标题
        $template->set_notyContent($message->getBody());//通知栏内容
        $template->set_notyIcon($message->getExtra('logo'));//通知栏logo
        $template->set_isBelled($message->isBell());//是否响铃
        $template->set_isVibrationed($message->isVibration());//是否震动
        $template->set_isCleared($message->isClearAble());//通知栏是否可清除

        //弹框
        $template->set_popTitle($message->getExtra('popTitle'));//弹框标题
        $template->set_popContent($message->getExtra('popContent'));//弹框内容
        $template->set_popImage($message->getExtra('popImage'));//弹框图片
        $template->set_popButton1($message->getExtra('popLeftButton'));//左键
        $template->set_popButton2($message->getExtra('popRightButton'));//右键

        //下载
        $template->set_loadIcon($message->getExtra('loadImage'));//弹框图片
        $template->set_loadTitle($message->getExtra('loadTitle'));
        $template->set_loadUrl($message->getExtra('loadUrl'));
        $template->set_isAutoInstall(false);
        $template->set_isActived(true);

        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        return $template;

    }


    /**
     * 安卓在通知栏打开连接
     *
     * @param Message $message
     * @return IGtLinkTemplate
     * @throws \Exception
     */
    protected function igtLinkTemplate(Message $message)
    {
        $template = new IGtLinkTemplate();

        $template->set_appId($this->appId); //应用appid
        $template->set_appkey($this->appkey); //应用$this->APPKEY
        $template->set_title($message->getTitle() . ''); //通知栏标题
        $template->set_text($message->getBody() . ''); //通知栏内容
        $template->set_logo($message->getExtra('logo')); //通知栏logo
        $template->set_isRing($message->isBell()); //是否响铃
        $template->set_isVibrate($message->isVibration()); //是否震动
        $template->set_isClearable($message->isClearAble()); //通知栏是否可清除
        $template->set_url($message->getExtra('linkUrl')); //打开连接地址

        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
        $apn = new IGtAPNPayload();
        $alertmsg = new DictionaryAlertMsg();
        $apn->alertMsg = $alertmsg;//"alertMsg";
        //$apn->badge = 11;
        $apn->actionLocKey = "启动";
        // $apn->category = "ACTIONABLE";
        // $apn->contentAvailable = 1;
        $apn->locKey = $message->getBody() . '';
        $apn->title = $message->getTitle() . '';
        $apn->titleLocArgs = array("titleLocArgs");
        $apn->titleLocKey = $message->getTitle() . '';
        $apn->body = $message->getBody() . '';
        $apn->customMsg = array("payload" => $message->getPayload());
        $apn->launchImage = "launchImage";
        $apn->locArgs = array("locArgs");

        // $apn->sound = ("test1.wav");

        $template->set_apnInfo($apn);

        return $template;
    }

    /**
     * @param Message $message
     * @return IGtTransmissionTemplate
     * @throws \Exception
     */
    protected function igtTransmissionTemplate(Message $message)
    {

        $template = new IGtTransmissionTemplate();
        $template->set_appId($this->appId); //应用appid
        $template->set_appkey($this->appkey); //应用$this->APPKEY
        $template->set_transmissionType(1); //透传消息类型
        $template->set_transmissionContent($message->getPayload()); //透传内容

        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        //APN简单推送
        //        $template = new IGtAPNTemplate();
        //        $apn = new IGtAPNPayload();
        //        $alertmsg=new SimpleAlertMsg();
        //        $alertmsg->alertMsg="";
        //        $apn->alertMsg=$alertmsg;
        ////        $apn->badge=2;
        ////        $apn->sound="";
        //        $apn->add_customMsg("payload","payload");
        //        $apn->contentAvailable=1;
        //        $apn->category="ACTIONABLE";
        //        $template->set_apnInfo($apn);
        //        $message = new IGtSingleMessage();

        if ($message->isVoIp()) {

            $voip = new VOIPPayload();
            $voip->setVoIPPayload($message->getVoIPPayload() . '');
            $template->set_apnInfo($voip);
        } else {
            //APN高级推送
            $apn = new IGtAPNPayload();
            $alertmsg = new DictionaryAlertMsg();
            $alertmsg->body = $message->getBody() . '';
            $alertmsg->actionLocKey = "ActionLockey";
            $alertmsg->locKey = "LocKey";
            $alertmsg->locArgs = array("locargs");
            $alertmsg->launchImage = "launchimage";

            // IOS8.2 支持
            $alertmsg->title = $message->getTitle() . '';
            $alertmsg->titleLocKey = "TitleLocKey";
            $alertmsg->titleLocArgs = array("TitleLocArg");
            $apn->alertMsg = $alertmsg;

            //$apn->badge = 7;
            $apn->sound = "";
            $apn->add_customMsg("payload", $message->getPayload() . '');
            $apn->contentAvailable = 1;
            $apn->category = "ACTIONABLE";

            $template->set_apnInfo($apn);
        }

        return $template;

    }

}