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

    /**
     * @var Config 完整的配置
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $appConfig    = $this->config->getAppConfig();
        $this->appId  = $appConfig['id'];
        $this->secret = $appConfig['secret'];
        $this->token  = $appConfig['token'];
    }

    /**
     * 切换APP
     *
     * @param string $name
     *
     * @return $this
     * @throws ApplicationException
     */
    public function use(string $name)
    {
        $config       = $this->config->getAppConfig($name);
        $this->appId  = $config['id'];
        $this->secret = $config['secret'];
        $this->token  = $config['token'];
        $this->config->offsetSet('default', $name);

        return $this;
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