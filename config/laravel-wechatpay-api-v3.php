<?php

return [
    'response_type' => env('WECHATPAY_API_V3_RESPONSE_TYPE', 'collection'),
    'log' => [
        'level' => env('WECHATPAY_API_V3_LOG_LEVEL', 'debug'),
        'file' => env('WECHATPAY_API_V3_LOG_FILE', storage_path('logs/laravel-wechatpay-api-v3.log')),
    ],
    'http' => [
        'verify' => env('WECHATPAY_API_V3_HTTP_VERIFY', false),
        'timeout' => env('WECHATPAY_API_V3_HTTP_TIMEOUT', 60),
    ],
    'merchant_id' => env('WECHATPAY_API_V3_MERCHANT_ID', ''),//商户号
    'merchant_serial_number' => env('WECHATPAY_API_V3_MERCHANT_SERIAL_NUM', ''),//商户API证书序列号
    'merchant_private_key ' => env('WECHATPAY_API_V3_MERCHANT_PRIVATE_KEY', ''),//商户私钥文件
    'wechatpay_certificate  ' => env('WECHATPAY_API_V3_WECAHTPAY_CERTIFICATE', ''),//平台公钥文件
];