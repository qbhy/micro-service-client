<?php
/**
 * User: qbhy
 * Date: 2018/8/8
 * Time: 下午5:42
 */

namespace Qbhy\MicroServiceClient;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

abstract class Service
{
    protected $serviceGuard;

    /** @var \GuzzleHttp\Client */
    protected $client;

    abstract public function getServiceName(): string;

    public function __construct(string $baseUri, ServiceGuard $serviceGuard)
    {
        $this->serviceGuard = $serviceGuard;

        $this->client = new Client([
            'base_uri' => $baseUri,
        ]);
    }

    /**
     * @return ServiceGuard
     */
    public function getServiceGuard(): ServiceGuard
    {
        return $this->serviceGuard;
    }

    protected function request($method, $uri, array $options = []): array
    {
        $authorization = $this->getServiceGuard()->authorization();

        return @json_decode(
            strval($this->client->request($method, "/{$this->getServiceName()}{$uri}", array_merge([
                RequestOptions::HEADERS => [
                    'Authorization' => $authorization,
                ]
            ], $options))->getBody()), true
        );
    }

}