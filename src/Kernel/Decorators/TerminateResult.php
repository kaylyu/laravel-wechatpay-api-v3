<?php

namespace Kaylyu\Wechatpay\ApiV3\Kernel\Decorators;

/**
 * Class TerminateResult.
 */
class TerminateResult
{
    /**
     * @var mixed
     */
    public $content;

    /**
     * FinallyResult constructor.
     *
     * @param mixed $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }
}
