<?php

namespace Merciall\Merci\App\Services;

use App\Classes\Merci\Exceptions\TypeError;

class Validate extends Service
{
    public function __construct(
        protected mixed $mixed
    ) {
        $this->mixed = $mixed;
    }

    public function type(string $type): void
    {
        if (!is_array($this->mixed))
            throw new TypeError("Variable to be evaluated must be of type array.");

        $array = $this->mixed;

        $check = match ($type) {
            'string', 'str' => 'is_string',
            'integer', 'int' => 'is_int',
            'array', 'arr' => 'is_array',
            'object', 'obj' => 'is_object'
        };

        foreach ($array as $key => $value)
            if (!$check($value))
                throw new TypeError("Merci\TypeError: All values of the given array must be of type '$type'. Key # $key is of type " . gettype($value) . ".");
    }
}
