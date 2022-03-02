<?php

namespace Merciall\Merci\App\Traits;

use Merciall\Merci\App\Services\Str;

trait Str_
{
    protected function Str(string $haystack): Str
    {
        return new Str($haystack);
    }
}
