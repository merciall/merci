<?php

namespace Merciall\Merci\App\Services;

class Service
{
    protected function validate(mixed $mixed): Validate
    {
        return new Validate($mixed);
    }
}
