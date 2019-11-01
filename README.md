# laravel-wechatpay-api-v3

## Installation

```shell
$ composer require kaylyu/laravel-wechatpay-api-v3:~1.0" -vvv
```

## Usage

```php
<?php

use Kaylyu\Wechatpay\ApiV3\Application;

$options = [
     [
         'response_type' => 'collection',
         'log' => [
             'level' => 'debug',
             'file' => 'logs/laravel-wechatpay-api-v3.log',
         ],
         'http' => [
             'verify' => false,
             'timeout' => 60
         ],
         'merchant_id' => '',//商户号
         'merchant_serial_number' => '',//商户API证书序列号
         'merchant_private_key ' => '',//商户私钥文件
         'wechatpay_certificate  ' => '',//平台公钥文件
     ]
];

$app = new Application($options);

//获取对象实列
$product = $app->device;
//调用方法
$product->inspections('');
```
