<?php

namespace Wzhanjun\Push\Contracts;

use Wzhanjun\Push\Contracts\TargetInterface as Target;
use Wzhanjun\Push\Contracts\MessageInterface as Message;

interface GatewayInterface
{
    public function toClient($client = null);

    public function send(Message $message, Target $target);

}