<?php

namespace Wzhanjun\Push\Gateways\Igetui;

use Wzhanjun\Push\Contracts\MessageInterface;

class Message implements MessageInterface
{

    private $messageType;

    /**
     * title
     *
     * @var string
     */
    private $title;


    /**
     * body
     *
     * @var string
     */
    private $body;


    /**
     * payload
     *
     * @var string
     */
    private $payload;


    /**
     * 是否支持离线
     *
     * @var
     */
    private $isOffline = true;

    /**
     * 过多久该消息离线失效（单位毫秒） 支持1-72小时*3600000秒，默认1小时
     *
     * @var
     */
    private $offlineExpireTime = 72 * 3600;

    /**
     *  0:联网方式不限;1:仅wifi;2:仅4G/3G/2G
     *
     * @var int
     */
    private $pushNetWorkType = 0;


    /**
     * 是否响铃
     *
     * @var
     */
    private $isBell = true;

    /**
     * 是否震动
     *
     * @var
     */
    private $isVibration = true;

    /**
     * @var bool
     */
    private $isClearAble = true;


    /**
     * 额外数据
     *
     * @var
     */
    private $extra;

    /**
     * 是否启动voip
     *
     * @var bool
     */
    private $isVoIP = false;

    /**
     * voip 消息体类型
     *
     * @var string
     */
    private $voIPPayload = '';

    /**
     * 透传启动类型
     *
     * 1为立即启动，2则广播等待客户端自启动
     *
     * @var int
     */
    private $transmissionType = 2;


    /**
     * @return mixed
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * @param $messageType
     * @return $this
     */
    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;

        return $this;
    }


    public function getIsOffline() :bool
    {
        return $this->isOffline;
    }

    /**
     * @param mixed $isOffline
     */
    public function setIsOffline($isOffline)
    {
        $this->isOffline = $isOffline;
    }

    /**
     * @return mixed
     */
    public function getOfflineExpireTime()
    {
        return $this->offlineExpireTime * 1000;
    }

    /**
     * @param $offlineExpireTime
     * @return $this
     */
    public function setOfflineExpireTime($offlineExpireTime)
    {
        if (intval($offlineExpireTime) > 0)
        {
            $this->offlineExpireTime = intval($offlineExpireTime);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getPushNetWorkType() :int
    {
        return $this->pushNetWorkType;
    }

    /**
     * @param int $pushNetWorkType
     */
    public function setPushNetWorkType($pushNetWorkType)
    {
        $this->pushNetWorkType = $pushNetWorkType;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function isBell()
    {
        return $this->isBell;
    }

    /**
     * @param mixed $isBell
     */
    public function setIsBell($isBell)
    {
        $this->isBell = $isBell;
    }

    /**
     * @return mixed
     */
    public function isVibration()
    {
        return $this->isVibration;
    }

    /**
     * @param $isVibration
     * @return $this
     */
    public function setIsVibration($isVibration)
    {
        $this->isVibration = $isVibration;

        return $this;
    }

    /**
     * @return bool
     */
    public function isClearAble(): bool
    {
        return $this->isClearAble;
    }

    /**
     * @param bool $isClearAble
     */
    public function setIsClearAble(bool $isClearAble)
    {
        $this->isClearAble = $isClearAble;
    }

    /**
     * @param $key
     * @return string
     */
    public function getExtra($key)
    {
        return $this->extra[$key] ?? '';
    }


    /**
     * @param $key
     * @param $val
     */
    public function setExtra($key, $val)
    {
        $this->extra[$key] = $val;
    }

    /**
     * @return bool
     */
    public function isVoIP(): bool
    {
        return $this->isVoIP;
    }

    /**
     * @param bool $isVoIP
     * @return $this
     */
    public function setIsVoIP(bool $isVoIP)
    {
        $this->isVoIP = $isVoIP;

        return $this;
    }

    /**
     * @return string
     */
    public function getVoIPPayload(): string
    {
        return $this->voIPPayload;
    }

    /**
     * @param string $voIPPayload
     * @return $this
     */
    public function setVoIPPayload(string $voIPPayload)
    {
        $this->voIPPayload = $voIPPayload;

        return $this;
    }

    /**
     * @return int
     */
    public function getTransmissionType(): int
    {
        return $this->transmissionType;
    }

    /**
     * @param int $transmissionType
     * @return Message
     */
    public function setTransmissionType(int $transmissionType)
    {
        $this->transmissionType = $transmissionType;

        return $this;
    }




}