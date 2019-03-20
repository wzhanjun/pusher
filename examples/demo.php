<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../examples/config.php';

$appId     = 'Zg4NFJPtXg5NmcqFdgNNG9';
$appSecret = 'HaFvsY2W3z97C6dTYPtJM8';
$appKey    = 'z9juM9yK4u66c2zMml1BEA';
$masterSecret = 'etAWkUvCsL706FRWuUlg11';
// 92ce1a5e91a871f763d2b8274b709053

$push = new \Wzhanjun\Push\Push($config);

try {
    $message = new \Wzhanjun\Push\Gateways\IGeTui\Message();

    $gateway = $push->gateway('igetui')
                    ->setDevice(123456789)
                    ->send($message)
                    ;

    var_dump($gateway);
} catch (Exception $exception) {
    var_dump($exception);
}


