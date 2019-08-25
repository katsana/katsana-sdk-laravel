<?php

namespace Katsana;

use Illuminate\Support\Arr;

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
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param array                                        $configurations
     */
    public function __construct($app, array $configurations)
    {
        parent::__construct($app);

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
        $client = new Sdk\Client($this->app->make('katsana.http'));

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
