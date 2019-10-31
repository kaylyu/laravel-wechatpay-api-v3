<?php

namespace Kaylyu\Wechatpay\ApiV3\Kernel\Http;

use Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\Exception;
use Kaylyu\Wechatpay\ApiV3\Kernel\Support\Collection;
use Kaylyu\Wechatpay\ApiV3\Kernel\Support\XML;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response.
 */
class Response extends GuzzleResponse
{
    /**
     * @var array
     */
    protected $forceArrayKeys = [];

    /**
     * @param $forceArrayKeys
     *
     * @return $this
     */
    public function setForceArrayKeys($forceArrayKeys)
    {
        $this->forceArrayKeys = $forceArrayKeys;

        return $this;
    }

    /**
     * @return array
     */
    public function getForceArrayKeys()
    {
        return $this->forceArrayKeys;
    }

    /**
     * @return string
     */
    public function getBodyContents()
    {
        $this->getBody()->rewind();
        $contents = $this->getBody()->getContents();
        $this->getBody()->rewind();

        return $contents;
    }

    /**
     * @param ResponseInterface $response
     * @author kaylv <kaylv@dayuw.com>
     * @return static
     */
    public static function buildFromPsrResponse(ResponseInterface $response)
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }

    /**
     * Build to json.
     *
     * @return string
     *
     * @throws Exception
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Build to array.
     *
     * @return array
     *
     * @throws Exception
     */
    public function toArray()
    {
        $content = $this->removeControlCharacters($this->getBodyContents());

        $array = json_decode($content, true, 512, JSON_BIGINT_AS_STRING);

        if (JSON_ERROR_NONE === json_last_error()) {
            return $array;
        }

        throw new Exception('Invalid Response Content: ' . $content);
    }

    /**
     * Get collection data.
     * @author kaylv <kaylv@dayuw.com>
     * @return Collection
     */
    public function toCollection()
    {
        return new Collection($this->toArray());
    }

    /**
     * @return object
     *
     * @throws Exception
     */
    public function toObject()
    {
        return json_decode($this->toJson());
    }

    /**
     * @return bool|string
     */
    public function __toString()
    {
        return $this->getBodyContents();
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function removeControlCharacters(string $content)
    {
        return \preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', $content);
    }
}
