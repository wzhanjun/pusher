<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../examples/config.php';

$push = new \Wzhanjun\Push\Push($config);

// 95eae00faa6fcd9325b7a433cfd03e0f

try {
    $message = new \Wzhanjun\Push\Gateways\IGeTui\Message();
    $message->setMessageType(\Wzhanjun\Push\Gateways\IGeTui\Message::MESSAGE_TYPE_NOTICE);
    $message->setBody('api push content');
    $message->setTitle('api push title');
    $message->setIsOffline(false);

    $target  = new \Wzhanjun\Push\Gateways\IGeTui\Target();
    // $target->setDeviceId('92ce1a5e91a871f763d2b8274b709053');
    $target->setDeviceId('95eae00faa6fcd9325b7a433cfd03e0f');
    // $target->setAll();
    // $target->setTargetList(['95eae00faa6fcd9325b7a433cfd03e0f'], \Wzhanjun\Push\Gateways\IGeTui\Target::PUSH_TYPE_DEVICE_LIST);

    // $target->setTags(['test']);

    // $result = $push->gateway('igetui')->bindAlias('test', '95eae00faa6fcd9325b7a433cfd03e0f');
   // $result = $push->gateway('igetui')->setDeviceTags(['test_03'], '95eae00faa6fcd9325b7a433cfd03e0f');
   // $result = $push->gateway('igetui')->queryAlias('95eae00faa6fcd9325b7a433cfd03e0f');
    /* $result = $push->gateway()->getPushResultByTaskList([
         'OSA-0325_Rq77I4mCiEA2h0bHR0isRA', 'OSS-0325_df97a54152642a35d089718e6c80641c'
     ]);*/
     // $result = $push->gateway()->getClientIdStatus('95eae00faa6fcd9325b7a433cfd03e0f');
     // $target->setAlias('test');
      $result = $push->gateway('igetui')->toApp('android')
                    ->send($message, $target);

    var_dump($result);
} catch (Exception $exception) {
    var_dump($exception->getMessage());
}


