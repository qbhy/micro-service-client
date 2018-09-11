<?php
/**
 * User: qbhy
 * Date: 2018/9/11
 * Time: 上午11:24
 */

return [
    // 默认应用
    'default'             => 'default',

    // 微服务 base uri
    'app_header'          => 'App',

    // 微服务 base uri
    'base_uri'            => env('MICRO_SERVICE_BASE_URI'),

    // 交易服务前缀
    'trade_center_prefix' => env('MICRO_SERVICE_TRADE_CENTER_PREFIX', 'settlement-internal'),

    // 用户服务前缀
    'user_center_prefix'  => env('MICRO_SERVICE_USER_CENTER_PREFIX', 'ucenter-internal'),

    // 应用服务前缀
    'app_center_prefix'   => env('MICRO_SERVICE_APP_CENTER_PREFIX', 'application'),

    // im 服务前缀
    'im_center_prefix'    => env('MICRO_SERVICE_IM_CENTER_PREFIX', 'im'),

    // 应用
    'applications'        => [
        'default' => [
            'id'     => env('MICRO_SERVICE_DEFAULT_APP_ID'),
            'secret' => env('MICRO_SERVICE_DEFAULT_APP_SECRET'),
            'token'  => env('MICRO_SERVICE_DEFAULT_APP_TOKEN'),
        ]
    ]
];