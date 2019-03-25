<?php

namespace Wzhanjun\Push\Gateways\IGeTui;

use Wzhanjun\Push\Contracts\TargetInterface;

class Target implements TargetInterface
{

    private $pushType;

    /**
     * 设备类型
     *
     * @var string
     */
    private $deviceType;

    /**
     *  按设备号推送
     *
     * @var  string
     */
     private $deviceId;


    /**
     * 按别名推送
     *
     * @var string
     */
    private $alias;

    /**
     * 按标签推送
     *
     * @var array
     */
    private $tags;

    /**
     * 推送给所有设备
     *
     * @var
     */
    private $all;


    /**
     * @var
     */
    private $targetList;


    /**
     * @return mixed
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param $deviceId
     * @return $this
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

        $this->pushType = self::PUSH_TYPE_DEVICE;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        $this->pushType = self::PUSH_TYPE_ALIAS;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;

        $this->pushType = self::PUSH_TYPE_TAGS;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * @return $this
     */
    public function setAll()
    {
        $this->all = true;

        $this->pushType = self::PUSH_TYPE_ALL;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * @param $deviceType
     * @return $this
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPushType()
    {
        return $this->pushType;
    }

    /**
     * @param $pushType
     * @return $this
     */
    public function setPushType($pushType)
    {
        $this->pushType = $pushType;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getTargetList()
    {
        return $this->targetList;
    }


    /**
     * @param $targetList
     * @param string $pushType
     * @return $this
     */
    public function setTargetList ($targetList, $pushType = self::PUSH_TYPE_DEVICE_LIST)
    {
        $this->targetList = $targetList;

        $this->pushType = $pushType;

        return $this;
    }

}