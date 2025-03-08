<?php

return [
    'timeout' => 5.0,

    'default' => [
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        'gateways' => [
            'qcloud',
        ] ,
    ],
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'qcloud' => [
            'sdk_app_id' => env('QCLOUD_SMS_APP_ID'),
            'app_key' => env('QCLOUD_SMS_APP_KEY'),
            'template' => env('QCLOUD_SMM_TEMPLATE'),
        ]
    ]
];
