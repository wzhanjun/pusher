<?php

namespace Wzhanjun\Push\Gateways;

use Wzhanjun\Push\Contracts\GatewayInterface;
use Wzhanjun\Push\Contracts\MessageInterface as Message;
use Wzhanjun\Push\Contracts\TargetInterface as Target;

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

    public function send(Message $message, Target $target)
    {
        // TODO: Implement send() method.
    }


    public function toApp($app = null)
    {
        // TODO: Implement toApp() method.
    }


}