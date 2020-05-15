<?php

return [
    'accessKeyId' => env('ACCESS_KEY_ID'),
    'accessKeySecret' => env('ACCESS_KEY_SECRET'),
    'push' => [
        "android" => [
            'appKey' => env('ANDROID_APP_PUSH_KEY'), //安卓移动推送APPkey
            'activity' => env('ANDROID_ACTIVITY'), //安卓接受推送activity
            'channel' => env('ANDROID_CHANNEL'), //安卓离线推送channel
            'openType' => env('ANDROID_OPEN_TYPE'), //安卓离线推送默认打开方式
            'notifyType' => env('ANDROID_NOTIFY_TYPE', 'BOTH'), //安卓打开提醒方式
        ],
        "iOS" => [
            'appKey' => env('IOS_APP_PUSH_KEY'), //iOS移动推送APPkey
            'apnsEnv' => env('IOS_APNS_ENV', 'DEV'), //DEV:开发环境 PRODUCT:正式环境
        ]
    ]
];
