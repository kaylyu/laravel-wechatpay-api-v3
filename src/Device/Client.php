<?php

namespace Kaylyu\Wechatpay\ApiV3\Device;

use Kaylyu\Wechatpay\ApiV3\Kernel\BaseClient;

/**
 * Class Product.
 */
class Client extends BaseClient
{
    /**
     * 获取出厂检查结果
     * @param $deviceSn
     * @author kaylv <kaylv@dayuw.com>
     * @return array|\Kaylyu\Wechatpay\ApiV3\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function inspections($deviceSn)
    {
        return $this->httpGet('v3/iotmanage/device-inspections/'.$deviceSn);
    }
}
