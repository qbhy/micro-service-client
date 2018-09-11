<?php
/**
 * User: qbhy
 * Date: 2018/7/24
 * Time: 下午3:28
 */

namespace Qbhy\MicroServiceClient;


class ServiceGuard
{
    const VERSION = 's.v1';

    /**
     * @var string 用户中心 APP ID
     */
    protected $appId;

    /**
     * @var string 用户中心 secret
     */
    protected $secret;

    /**
     * @var string 用户中心 token
     */
    protected $token;

    public function __construct(string $appId, string $secret, string $token)
    {
        $this->appId  = $appId;
        $this->secret = $secret;
        $this->token  = $token;
    }

    public function authorization()
    {
        list($randStr, $signature) = $this->signature();

        return sprintf('%s+%s+%s+%s', $randStr, $signature, $this->appId, ServiceGuard::VERSION);
    }

    protected function signature()
    {
        $randStr = str_random(rand(8, 16));

        $signature = sha1($this->secret . $randStr . $this->token);

        return [$randStr, $signature];
    }

}