<?php

namespace Phlux\Laravel\Facades;

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
