<?php

namespace Katsana\Tests;

class ManagerTest extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        config(['services.katsana' => [
            'environment' => 'carbon',
        ]]);

        $stub = $this->app['katsana.manager'];

        $this->assertSame('laravel', $stub->getDefaultDriver());
    }

    /** @test */
    public function it_can_use_laravel_driver()
    {
        config(['services.katsana' => [
            'client_id' => static::CLIENT_ID,
            'client_secret' => static::CLIENT_SECRET,
            'access_token' => static::ACCESS_TOKEN,
            'environment' => 'carbon',
        ]]);

        $stub = $this->app['katsana.manager']->driver('laravel');

        $this->assertSame(static::CLIENT_ID, $stub->getClientId());
        $this->assertSame(static::CLIENT_SECRET, $stub->getClientSecret());
        $this->assertSame(static::ACCESS_TOKEN, $stub->getAccessToken());

        $this->assertSame('https://carbon.api.katsana.com', $stub->getApiEndpoint());
        $this->assertSame(config('services.katsana'), $this->app['katsana.manager']->config());
    }

    /** @test */
    public function it_can_use_sdk_driver()
    {
        config(['services.katsana' => [
            'client_id' => static::CLIENT_ID,
            'client_secret' => static::CLIENT_SECRET,
            'access_token' => static::ACCESS_TOKEN,
            'environment' => 'carbon',
        ]]);

        $stub = $this->app['katsana.manager']->driver('sdk');

        $this->assertNull($stub->getClientId());
        $this->assertNull($stub->getClientSecret());
        $this->assertNull($stub->getAccessToken());

        $this->assertSame('https://carbon.api.katsana.com', $stub->getApiEndpoint());

        $this->assertSame('carbon', $this->app['katsana.manager']->config('environment'));
    }
}
