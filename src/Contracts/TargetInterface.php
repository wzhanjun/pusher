<?php

namespace Wzhanjun\Push\Contracts;


/**
 * @method  setDeviceId();
 * @method  getDeviceId();
 *
 * @method getAlias()
 * @method setAlias()
 *
 * Interface TargetInterface
 * @package Wzhanjun\Push\Contracts
 */
interface TargetInterface
{

    /**
     * deviceType
     */
    const DEVICE_TYPE_ALL      = 'ALL';
    const DEVICE_TYPE_ANDROID  = 'ANDROID';
    const DEVICE_TYPE_IOS      = 'IOS';

    /**
     * pushType
     */
    const PUSH_TYPE_DEVICE          = 'device';
    const PUSH_TYPE_ALIAS           = 'alias';
    const PUSH_TYPE_TAGS            = 'tags';
    const PUSH_TYPE_DEVICE_LIST     = 'deviceList';
    const PUSH_TYPE_ALIAS_LIST      = 'aliasList';
    const PUSH_TYPE_ALL             = 'all';

    public function setDeviceType($deviceType);

    public function getDeviceType();

    public function getPushType();

}