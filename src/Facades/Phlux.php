<?php

namespace Phlux\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see Phlux\Phlux
 */
class Phlux extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'phlux';
    }
}
