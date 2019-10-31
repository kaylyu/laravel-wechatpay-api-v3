<?php

namespace Kaylyu\Wechatpay\ApiV3\Kernel;

use Kaylyu\Wechatpay\ApiV3\Kernel\Http\Response;
use Kaylyu\Wechatpay\ApiV3\Kernel\Support\Arr;
use Kaylyu\Wechatpay\ApiV3\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;

/**
 * Class BaseClient.
 */
class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var \Kaylyu\Wechatpay\ApiV3\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     *
     * @param \Kaylyu\Wechatpay\ApiV3\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array $query
     * @param array $forceArrayKeys
     *
     * @return \Psr\Http\Message\ResponseInterface|\Kaylyu\Wechatpay\ApiV3\Kernel\Support\Collection|array|object|string
     *
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\Exception
     */
    public function httpGet(string $url, array $query = [], array $forceArrayKeys = [])
    {
        //准备参数
        $query = $this->prepareParameters($query);

        return $this->request($url, 'GET', ['query' => $query],false, $forceArrayKeys);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array $data
     * @param array $forceArrayKeys
     *
     * @return \Psr\Http\Message\ResponseInterface|\Kaylyu\Wechatpay\ApiV3\Kernel\Support\Collection|array|object|string
     *
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\Exception
     */
    public function httpPost(string $url, array $data = [], array $forceArrayKeys = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data],false, $forceArrayKeys);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param array $data
     * @param array $forceArrayKeys
     *
     * @return \Psr\Http\Message\ResponseInterface|\Kaylyu\Wechatpay\ApiV3\Kernel\Support\Collection|array|object|string
     *
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\Exception
     */
    public function httpPostJson(string $url, array $data = [], array $forceArrayKeys = [])
    {
        return $this->request($url, 'POST', ['json' => $data], false, $forceArrayKeys );
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array $files
     * @param array $form
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\Kaylyu\Wechatpay\ApiV3\Kernel\Support\Collection|array|object|string
     *
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\Exception
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', [
            'query' => $query,
            'multipart' => $multipart,
            'connect_timeout' => 30,
            'timeout' => 30,
            'read_timeout' => 30
        ]);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     * @param array $forceArrayKeys
     *
     * @return \Psr\Http\Message\ResponseInterface|\Kaylyu\Wechatpay\ApiV3\Kernel\Support\Collection|array|object|string
     *
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\Exception
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false, array $forceArrayKeys = [])
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response
            : $this->castResponseToType($response, $this->app->config->get('response_type'), $forceArrayKeys);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     *
     * @return \Kaylyu\Wechatpay\ApiV3\Kernel\Http\Response
     *
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Wechatpay\ApiV3\Kernel\Exceptions\Exception
     */
    public function requestRaw(string $url, string $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter);
    }

    /**
     * @param $data
     * @author kaylv <kaylv@dayuw.com>
     * @return array
     */
    public function prepareParameters($data = [])
    {
        //添加时间戳
        $data = array_merge($data, [
            'appKey' => $this->app->getAppKey(),
            'messageFormat' => $this->app->getMessageFormat(),
            'timestamp' => time(),
        ]);

        //获取secret
        $secret = $this->app->getSecret();

        //添加签名
        $data = array_merge($data, [
            'sign' => Support\generate_sign([$secret, Arr::get($data, 'timestamp'), $secret])
        ]);

        return $data;
    }
}
