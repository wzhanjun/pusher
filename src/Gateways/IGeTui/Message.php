<?php

namespace Wzhanjun\Push\Gateways\IGeTui;

use Wzhanjun\Push\Contracts\MessageInterface;

class Message implements MessageInterface
{
    /**
     * 是否支持离线
     *
     * @var
     */
    protected $isOffline;

    /**
     * 过多久该消息离线失效（单位毫秒） 支持1-72小时*3600000秒，默认1小时
     *
     * @var
     */
    protected $offlineExpireTime;

    /**
     *  0:联网方式不限;1:仅wifi;2:仅4G/3G/2G
     *
     * @var int
     */
    protected $pushNetWorkType = 0;


    protected $data;

    /**
     * @return mixed
     */
    public function getIsOffline()
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
        return $this->offlineExpireTime;
    }

    /**
     * @param mixed $offlineExpireTime
     */
    public function setOfflineExpireTime($offlineExpireTime)
    {
        $this->offlineExpireTime = $offlineExpireTime;
    }

    /**
     * @return int
     */
    public function getPushNetWorkType()
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
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}