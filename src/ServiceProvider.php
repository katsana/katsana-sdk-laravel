<?php

namespace Katsana;

use Http\Client\Common\HttpMethodsClient;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravie\Codex\Discovery;

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

        $this->app->singleton('katsana', function (Container $app) {
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
        return Discovery::client();
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
