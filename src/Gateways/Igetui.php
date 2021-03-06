<?php

namespace Wzhanjun\Push\Gateways;

use Wzhanjun\Push\Support\Config;
use Wzhanjun\Push\Exceptions\Exception;
use Wzhanjun\Push\Gateways\Igetui\Client;
use Wzhanjun\Push\Contracts\GatewayInterface;
use Wzhanjun\Push\Contracts\TargetInterface as Target;
use Wzhanjun\Push\Contracts\MessageInterface as Message;

class Igetui implements GatewayInterface
{

    protected $config;

    /**
     * Send device type.
     *
     * @var
     */
    protected $deviceType;


    /**
     * clientConfig
     *
     * @var
     */
    protected $appConfig;

    /**
     * IGeTui constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        $this->toApp();
    }

    /**
     * @param Message $message
     * @param Target $target
     * @return mixed|null
     * @throws \Exception
     */
    public function send(Message $message, Target $target)
    {
        $client = new Client($this->appConfig);

        switch ($target->getPushType())
        {
            case Target::PUSH_TYPE_DEVICE_LIST:
            case Target::PUSH_TYPE_ALIAS_LIST:
                $result = $client->pushMessageToList($message, $target);
                break;
            case Target::PUSH_TYPE_TAGS:
            case Target::PUSH_TYPE_ALL:
                $result = $client->pushMessageToApp($message, $target);
                break;
            case Target::PUSH_TYPE_DEVICE:
            case Target::PUSH_TYPE_ALIAS:
                $result = $client->pushMessageToSingle($message, $target);
                break;
            default:
                throw new Exception(sprintf('PUSH_TYPE %s not support', $target->getPushType()));
        }

        return $result;
    }


    /**
     * 绑定别名
     *
     * @param $alias
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function bindAlias($alias, $deviceId)
    {
        $client = new Client($this->appConfig);

        return $client->bindAlias($alias, $deviceId);
    }


    /**
     * 查询别名
     *
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function queryAlias($deviceId)
    {
        $client = new Client($this->appConfig);

        return $client->queryAlias($deviceId);
    }


    /**
     * 获取设备状态
     *
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function getClientIdStatus($deviceId)
    {
        $client = new Client($this->appConfig);

        return $client->getClientIdStatus($deviceId);
    }


    /**
     * 绑定标签
     *
     * @param array $tagList
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function setDeviceTags(array $tagList, $deviceId)
    {
        $client = new Client($this->appConfig);

        return $client->setDeviceTags($tagList, $deviceId);
    }


    /**
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function getDeviceTags($deviceId)
    {
        $client = new Client($this->appConfig);

        return $client->getDeviceTags($deviceId);
    }

    /**
     * 获取任务结果
     *
     * @param array $taskList
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function getPushResultByTaskList(array $taskList)
    {
        $client = new Client($this->appConfig);

        return $client->getPushResultByTaskList($taskList);
    }


    /**
     * @param null $app
     * @return $this|GatewayInterface
     * @throws Exception
     */
    public function toApp($app = null)
    {
        if (!$app)
        {
            $app = $this->config->get('default_app');
        }

        $config = $this->config->get("apps.{$app}");

        if (empty($config))
        {
            throw new Exception(sprintf("app %s config not exist", $app));
        }

        $this->appConfig = new Config($config);

        return $this;
    }

}