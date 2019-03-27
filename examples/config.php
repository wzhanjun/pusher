<?php

return [

    'default' => 'igetui',

    'pusher' => [

        'igetui' => [
            'url'           => 'http://sdk.open.api.getui.net/apiex.htm', //  http://sdk.open.api.igexin.com/apiex.htm',
            'version'       => '4.1.0.0',
            'timeout'       => 30,
            'default_app'   => 'demo',
            'apps'          => [
                'demo'      => [
                    'app_id'        => 'Zg4NFJPtXg5NmcqFdgNNG9',
                    'app_key'       => 'z9juM9yK4u66c2zMml1BEA',
                    'app_secret'    => 'HaFvsY2W3z97C6dTYPtJM8',
                    'master_secret' => 'etAWkUvCsL706FRWuUlg11',
                ],
                'android'      => [
                    'app_id'        => 'ORCnwcFgdd54NZ0aOpyrw1',
                    'app_key'       => 'B6PDREGraF9e6PebZrXXN7',
                    'app_secret'    => 'hLUQBc185V6TXFfChUkpm3',
                    'master_secret' => 'w79g4Xm5Wl8MWZlfg3oQr5',
                ],
            ],
        ],

        // apns
        'apns' => [
            'default_app'    => 'maiguoer',
            'apps'           => [
                'maiguoer'   => [
                    'url' => 'ssl://gateway.sandbox.push.apple.com:2195',
                    'certificate'   => __DIR__ . '/appStore_apns_Development.pem',
                ],
            ],
        ],
    ],

];