<?php

namespace Kaylyu\Wechatpay\ApiV3\Kernel\Contracts;

/**
 * Interface EventHandlerInterface.
 */
interface EventHandlerInterface
{
    /**
     * @param mixed $payload
     */
    public function handle($payload = null);
}
