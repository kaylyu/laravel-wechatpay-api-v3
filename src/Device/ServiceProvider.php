<?php

namespace Kaylyu\Wechatpay\ApiV3\Device;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['device'] = function ($app) {
            return new Client($app);
        };
    }
}
