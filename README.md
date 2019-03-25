## 推送

#### 安装

	composer require wzhanjun/pusher

#### 配置

```

	'default' => 'igetui',

    'pusher' => [

        'igetui' => [
            'url'               => 'http://sdk.open.api.getui.net/apiex.htm', //  http://sdk.open.api.igexin.com/apiex.htm',
            'version'           => '4.1.0.0',
            'timeout'           => 30,
            'default_client'    => 'demo',
            'clients'           => [
                'demo'      => [
                    'app_id'        => 'XXXXXXXXXXXXXXXXXXXXX',
                    'app_key'       => 'XXXXXXXXXXXXXXXXXXXXX',
                    'app_secret'    => 'XXXXXXXXXXXXXXXXXXXXX',
                    'master_secret' => 'XXXXXXXXXXXXXXXXXXXXX',
                ],
                'android'      => [
                    'app_id'        => 'XXXXXXXXXXXXXXXXXXXXX',
                    'app_key'       => 'XXXXXXXXXXXXXXXXXXXXX',
                    'app_secret'    => 'XXXXXXXXXXXXXXXXXXXXX',
                    'master_secret' => 'XXXXXXXXXXXXXXXXXXXXX',
                ],
            ]
        ],

    ],



```


#### 使用

```
	
	$push = new \Wzhanjun\Push\Push($config);

 	$message = new \Wzhanjun\Push\Gateways\IGeTui\Message();
    $message->setMessageType(\Wzhanjun\Push\Gateways\IGeTui\Message::MESSAGE_TYPE_NOTICE);
    $message->setBody('api push content');
    $message->setTitle('api push title');

    $target  = new \Wzhanjun\Push\Gateways\IGeTui\Target();
    $target->setDeviceId('92ce1a5e91a871f763d2b8274b709053');

  	$result = $push->gateway('igetui')->toClient('demo')
                    ->send($message, $target);



```