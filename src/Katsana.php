<?php

namespace Katsana;

use Illuminate\Support\Facades\Facade;

class Katsana extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'katsana';
    }
}
