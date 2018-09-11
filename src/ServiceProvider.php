<?php

namespace Qbhy\MicroServiceClient;

use Qbhy\MicroServiceClient\ApplicationCenter\AppCenterService;
use Qbhy\MicroServiceClient\TradeCenter\IMCenterService;
use Qbhy\MicroServiceClient\TradeCenter\TradeCenterService;
use Qbhy\MicroServiceClient\UserCenter\JwtParser\AuthHeaders;
use Qbhy\MicroServiceClient\UserCenter\JwtParser\InputSource;
use Qbhy\MicroServiceClient\UserCenter\JwtParser\LumenRouteParams;
use Qbhy\MicroServiceClient\UserCenter\JwtParser\QueryString;
use Qbhy\MicroServiceClient\UserCenter\UserCenterEncrypter;
use Qbhy\MicroServiceClient\UserCenter\UserCenterService;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Qbhy\SimpleJwt\Encoders\Base64UrlSafeEncoder;
use Qbhy\SimpleJwt\Interfaces\Encoder;
use Qbhy\SimpleJwt\JWTManager;
use \Qbhy\MicroServiceClient\UserCenter\JwtParser\Parser as ParserManager;

class ServiceProvider extends BaseServiceProvider
{
    protected $config;

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/micro-service-client.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([$source => base_path('config/micro-service-client.php')], 'micro-service-client');
        }

        $this->mergeConfigFrom($source, 'micro-service-client');
    }

    protected function getConfig()
    {
        if (null !== $this->config) {
            $this->config = $this->app->make('config')->get('micro-service-client');
        }

        return $this->config;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();

        $this->app->singleton(ServiceGuard::class, function () {

            $config    = $this->getConfig();
            $default   = $this->app->make(Request::class)->header($config['app_header'], $config['default']);
            $appConfig = $config['applications'][$default];

            return new ServiceGuard($appConfig['id'], $appConfig['secret'], $appConfig['token']);
        });

        $this->registerUserCenter();
        $this->registerTradeCenter();
        $this->registerApplicationService();
        $this->registerImCenter();
    }

    public function registerApplicationService()
    {
        $this->app->singleton(AppCenterService::class, function () {
            return new AppCenterService($this->getConfig()['base_uri'], $this->app->make(ServiceGuard::class));
        });
    }

    public function registerTradeCenter()
    {
        $this->app->singleton(TradeCenterService::class, function () {
            return new TradeCenterService($this->getConfig()['base_uri'], $this->app->make(ServiceGuard::class));
        });
    }

    public function registerImCenter()
    {
        $this->app->singleton(IMCenterService::class, function () {
            return new IMCenterService($this->getConfig()['base_uri'], $this->app->make(ServiceGuard::class));
        });
    }

    protected function registerUserCenter()
    {
        $this->app->singleton(Encoder::class, function () {
            return new Base64UrlSafeEncoder();
        });


        $this->app->singleton(ParserManager::class, function () {
            return new ParserManager(
                $this->app->make(Request::class),
                [
                    new LumenRouteParams(),
                    new AuthHeaders(),
                    new InputSource(),
                    new QueryString(),
                ]
            );
        });

        $this->app->singleton(JWTManager::class, function () {
            /** @var Base64UrlSafeEncoder $encoder */
            $encoder = $this->app->make(Encoder::class);
            $config  = $this->getConfig();
            $default = $this->app->make(Request::class)->header($config['app_header'], $config['default']);

            return new JWTManager(
                new UserCenterEncrypter($config['applications'][$default]['secret'], $encoder),
                $encoder
            );
        });

        $this->app->singleton(UserCenterService::class, function () {
            return new UserCenterService($this->getConfig()['base_uri'], $this->app->make(ServiceGuard::class));
        });

        $this->registerUserCenterAuth();
    }

    protected function registerUserCenterAuth()
    {
        $this->app->make('auth')->provider('user_center', function (Container $app, $config) {
            return new UserCenter\Auth\UserCenterUserProvider($config['model']);
        });

        $this->app->make('auth')->extend('user_center', function (Container $app, $name, $config) {
            return new  UserCenter\Auth\UserCenterGuard(
                $app->make(JWTManager::class),
                $app->make('auth')->createUserProvider($config['provider']),
                $app->make(Request::class)
            );
        });
    }
}
