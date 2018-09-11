<?php

namespace Qbhy\MicroServiceClient;

use Qbhy\MicroServiceClient\ApplicationCenter\AppCenterService;
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
    protected $baseUri;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->baseUri = env('USER_CENTER_BASE_URI');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ServiceGuard::class, function () {
            return new ServiceGuard(env('USER_CENTER_APP_ID'), env('USER_CENTER_APP_SECRET'), env('USER_CENTER_APP_TOKEN'));
        });

        $this->registerUserCenter();
        $this->registerTradeCenter();
        $this->registerApplicationService();
    }

    public function registerApplicationService()
    {
        $this->app->singleton(AppCenterService::class, function () {
            return new AppCenterService(env('APP_CENTER_BASE_URI', $this->baseUri), $this->app->make(ServiceGuard::class));
        });
    }

    public function registerTradeCenter()
    {
        $this->app->singleton(TradeCenterService::class, function () {
            return new TradeCenterService(env('TRADE_CENTER_BASE_URI', $this->baseUri), $this->app->make(ServiceGuard::class));
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
            return new JWTManager(
                new UserCenterEncrypter(env('USER_CENTER_APP_SECRET'), $encoder),
                $encoder
            );
        });

        $this->app->singleton(UserCenterService::class, function () {
            return new UserCenterService($this->baseUri, $this->app->make(ServiceGuard::class));
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
