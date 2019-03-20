<?php

namespace Wzhanjun\Push\Gateways;

use Wzhanjun\Push\Contracts\GatewayInterface;
use Wzhanjun\Push\Contracts\MessageInterface as Message;

class AliPush implements GatewayInterface
{
    public function setDeviceType($deviceType)
    {
        // TODO: Implement setDeviceType() method.
    }

    public function setSendTo($field, $value = true)
    {
        // TODO: Implement setSendTo() method.
    }

    public function send(Message $message)
    {
        return 'alipush send';
    }


}