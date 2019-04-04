<?php

namespace Katsana;

class Manager extends \Illuminate\Support\Manager
{
    /**
     * KATSANA service configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Create a new manager instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param array                                        $config
     */
    public function __construct($app, array $config)
    {
        parent::__construct($app);

        $this->config = $config;
    }

    /**
     * Create laravel driver.
     *
     * @return \Katsana\Sdk\Client
     */
    protected function createLaravelDriver(): Sdk\Client
    {
        return \tap($this->createHttpClient(), function ($client) {
            if (isset($this->config['client_id']) || isset($this->config['client_secret'])) {
                $client->setClientId($this->config['client_id'])
                        ->setClientSecret($this->config['client_secret']);
            }

            if (isset($this->config['access_token'])) {
                $client->setAccessToken($this->config['access_token']);
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
     * @param array $config
     *
     * @return \Katsana\Sdk\Client
     */
    protected function createHttpClient(): Sdk\Client
    {
        $client = new Sdk\Client($this->app->make('katsana.http'));

        if (($this->config['environment'] ?? 'production') === 'carbon') {
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
