<?php

namespace Katsana;

use Http\Client\Common\HttpMethodsClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Katsana\Sdk\Client;
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
        $this->app->singleton('katsana', function (Application $app) {
            return $this->getSdkClient($app->make('config')->get('services.katsana'));
        });

        $this->app->singleton('katsana.http', function (Application $app) {
            return $this->createHttpClient();
        });
    }

    /**
     * Get KATSANA SDK Client.
     *
     * @param array $config
     *
     * @return \Katsana\Sdk\Client
     */
    protected function getSdkClient(array $config): Client
    {
        $client = new Client($this->createHttpClient());

        if (isset($config['client_id']) || isset($config['client_secret'])) {
            $client->setClientId($config['client_id'])
                    ->setClientSecret($config['client_secret']);
        }

        if (isset($config['access_token'])) {
            $client->setAccessToken($config['access_token']);
        }

        if (($config['environment'] ?? 'production') === 'carbon') {
            $client->useCustomApiEndpoint('https://carbon.api.katsana.com');
        }

        return $client;
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
        return ['katsana', 'katsana.http'];
    }
}
