<?php

namespace Katsana;

use Katsana\Sdk\Client;
use Laravie\Codex\Discovery;
use Http\Client\Common\HttpMethodsClient;
use Illuminate\Contracts\Foundation\Application;
use Http\Adapter\Guzzle6\Client as GuzzleHttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHttpClient();

        $this->app->singleton('katsana', function (Application $app) {
            return $this->getSdkClient($app->make('config')->get('services.katsana'));
        });
    }

    /**
     * Get KATSANA SDK Client.
     *
     * @param  array  $config
     *
     * @return \Katsana\Sdk\Client
     */
    protected function getSdkClient(array $config)
    {
        $client = Client::make(
            $config['client_id'],
            $config['client_secret']
        );

        if (isset($config['environment']) && $config['environment'] === 'carbon') {
            $client->useCustomApiEndpoint('https://carbon.api.katsana.com');
        }

        return $client;
    }

    /**
     * Register HTTP Client.
     *
     * @return void
     */
    protected function registerHttpClient()
    {
        Discovery::override(
            new HttpMethodsClient(
                new GuzzleHttpClient(), new GuzzleMessageFactory()
            )
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['katsana'];
    }
}
