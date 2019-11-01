<?php

namespace Kaylyu\Wechatpay\ApiV3\Kernel\Validators;

use WechatPay\GuzzleMiddleware\Validator;

/**
 * Created by PhpStorm.
 * User: kaylv <kaylv@dayuw.com>
 * Date: 2019/11/1
 * Time: 9:36
 */
class NoopValidator implements Validator
{
    public function validate(\Psr\Http\Message\ResponseInterface $response)
    {
        return true;
    }
}