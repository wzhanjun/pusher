<?php

namespace Wzhanjun\Push\Gateways;

use Wzhanjun\Push\Contracts\GatewayInterface;
use Wzhanjun\Push\Contracts\MessageInterface as Message;
use Wzhanjun\Push\Contracts\TargetInterface as Target;

class Apns implements GatewayInterface
{

    public function send(Message $message, Target $target)
    {
        // TODO: Implement send() method.
    }


    public function toClient($client = null)
    {
        // TODO: Implement toClient() method.
    }


}