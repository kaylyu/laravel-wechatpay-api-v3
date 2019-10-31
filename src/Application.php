<?php

namespace Kaylyu\Wechatpay\ApiV3;

use Kaylyu\Wechatpay\ApiV3\Kernel\ServiceContainer;

/**
 * @property Device\Client  $order
 *
 * Class Application.
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Device\ServiceProvider::class,
    ];
}
