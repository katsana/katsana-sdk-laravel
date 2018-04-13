<?php

namespace Katsana\TestCase;

use Orchestra\Testbench\TestCase as Testbench;

abstract class TestCase extends Testbench
{
    const CLIENT_ID = 'homestead';
    const CLIENT_SECRET = 'secret';
    const ACCESS_TOKEN = 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF';

    /**
     * Get package aliases.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Katsana' => \Katsana\Katsana::class,
        ];
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Katsana\ServiceProvider::class,
        ];
    }
}
