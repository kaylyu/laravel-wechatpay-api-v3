<?php

namespace Kaylyu\Wechatpay\ApiV3\Tests\Device;

use Kaylyu\Wechatpay\ApiV3\Application;
use Kaylyu\Wechatpay\ApiV3\Tests\TestCase;

class ClientTest extends TestCase
{
    protected $application;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->application = new Application(
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
        );
    }

    /**
     * 订单校验
     * @author kaylv <kaylv@dayuw.com>
     */
    public function ValidateOrder(){

        $result = $this->application->device->inspections('');

        $this->assertEquals(0, $result);
    }
}