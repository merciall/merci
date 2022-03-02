<?php

namespace Merciall\Merci\Facades;

use Illuminate\Support\Facades\Facade;
use Merciall\Merci\App\Services\Str;

/**
 * @method static Str Str()
 */
class Merci extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'merci';
    }
}
