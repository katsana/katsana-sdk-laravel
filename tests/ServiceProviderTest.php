<?php

namespace Katsana\TestCase;

use Katsana\Katsana;
use Katsana\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $katsana = new ServiceProvider(null);

        $this->assertTrue($katsana->isDeferred());
        $this->assertSame(['katsana'], $katsana->provides());
    }

    /** @test */
    public function it_provides_the_service_using_client_credential()
    {
        config(['services.katsana' => [
            'client_id' => static::CLIENT_ID,
            'client_secret' => static::CLIENT_SECRET,
        ]]);

        $this->assertInstanceOf('Katsana\Sdk\Client', $this->app->make('katsana'));
        $this->assertSame('https://api.katsana.com', Katsana::getApiEndpoint());
        $this->assertSame(static::CLIENT_ID, Katsana::getClientId());
        $this->assertSame(static::CLIENT_SECRET, Katsana::getClientSecret());
        $this->assertNull(Katsana::getAccessToken());
        $this->assertSame('v1', Katsana::getApiVersion());
    }


    /** @test */
    public function it_provides_the_service_using_access_token()
    {
        config(['services.katsana' => [
            'access_token' => static::ACCESS_TOKEN,
        ]]);

        $this->assertInstanceOf('Katsana\Sdk\Client', $this->app->make('katsana'));
        $this->assertSame('https://api.katsana.com', Katsana::getApiEndpoint());
        $this->assertSame(static::ACCESS_TOKEN, Katsana::getAccessToken());
        $this->assertSame('v1', Katsana::getApiVersion());
    }

    /** @test */
    public function it_can_use_carbon_environment()
    {
        config(['services.katsana' => [
            'access_token' => static::ACCESS_TOKEN,
            'environment' => 'carbon',
        ]]);

        $this->assertSame('https://carbon.api.katsana.com', Katsana::getApiEndpoint());
    }
}
