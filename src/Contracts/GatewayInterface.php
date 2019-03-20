<?php


namespace Wzhanjun\Push\Contracts;

use Wzhanjun\Push\Contracts\MessageInterface as Message;

interface GatewayInterface
{
    /**
     * deviceType
     */
    const DEVICE_TYPE_ALL      = 'ALL';
    const DEVICE_TYPE_ANDROID  = 'ANDROID';
    const DEVICE_TYPE_IOS      = 'IOS';

    public function setDeviceType($deviceType);

    public function setSendTo($field, $value = true);

    public function send(Message $message);

}