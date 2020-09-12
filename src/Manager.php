<?php

namespace Katsana;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\Container;

class Manager extends \Illuminate\Support\Manager
{
    /**
     * KATSANA service configuration.
     *
     * @var array
     */
    protected $configurations = [];

    /**
     * Create a new manager instance.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @param array                                     $configurations
     */
    public function __construct(Container $container, array $configurations)
    {
        parent::__construct($container);

        $this->configurations = $configurations;
    }

    /**
     * Get the configuration.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function config(?string $key = null)
    {
        return Arr::get($this->configurations, $key);
    }

    /**
     * Create laravel driver.
     *
     * @return \Katsana\Sdk\Client
     */
    protected function createLaravelDriver(): Sdk\Client
    {
        return \tap($this->createHttpClient(), function ($client) {
            if (isset($this->configurations['client_id']) || isset($this->configurations['client_secret'])) {
                $client->setClientId($this->configurations['client_id'])
                        ->setClientSecret($this->configurations['client_secret']);
            }

            if (isset($this->configurations['access_token'])) {
                $client->setAccessToken($this->configurations['access_token']);
            }
        });
    }

    /**
     * Create SDK driver.
     *
     * @return \Katsana\Sdk\Client
     */
    protected function createSdkDriver(): Sdk\Client
    {
        return $this->createHttpClient();
    }

    /**
     * Get KATSANA SDK Client.
     *
     * @return \Katsana\Sdk\Client
     */
    protected function createHttpClient(): Sdk\Client
    {
        $container = $this->container ?? $this->app;
        $client = new Sdk\Client($container->make('katsana.http'));

        if (($this->configurations['environment'] ?? 'production') === 'carbon') {
            $client->useCustomApiEndpoint('https://carbon.api.katsana.com');
        }

        return $client;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'laravel';
    }
}
