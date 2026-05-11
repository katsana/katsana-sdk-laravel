<?php

namespace Katsana;

use GuzzleHttp\Psr7\HttpFactory;
use Http\Adapter\Guzzle7\Client as Guzzle7Client;
use Http\Client\Common\HttpMethodsClient;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('katsana.http', function () {
            return $this->createHttpClient();
        });

        $this->app->singleton('katsana.manager', static function (Container $app) {
            return new Manager(
                $app, $app->make('config')->get('services.katsana', ['environment' => 'production'])
            );
        });

        $this->app->singleton('katsana', static function (Container $app) {
            return $app->make('katsana.manager')->driver();
        });
    }

    /**
     * Register HTTP Client.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    protected function createHttpClient(): HttpMethodsClient
    {
        $factory = new HttpFactory();

        return new HttpMethodsClient(
            Guzzle7Client::createWithConfig([]),
            $factory,
            $factory
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['katsana', 'katsana.http', 'katsana.manager'];
    }
}
