<?php

namespace Katsana;

use Illuminate\Support\Facades\Facade;

/**
 * @method mixed config(string|null $key = null)
 * @method \Katsana\Sdk\Client driver(string|null $driver = null)
 * @method \Laravie\Codex\Contracts\Request uses(string $service, string|null $version = null)
 * @method \Laravie\Codex\Contracts\Request via(\Laravie\Codex\Contracts\Request $request)
 * @method \Katsana\Sdk\Client onTimeZone(string $timeZoneCode)
 * @method \Katsana\Sdk\Client useCustomApiEndpoint(string $endpoint)
 *
 * @see \Katsana\Manager
 */
class Katsana extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'katsana.manager';
    }
}
