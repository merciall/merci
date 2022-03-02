<?php

namespace Merciall\Merci\App\Services\Str;

use Merciall\Merci\App\Services\Str;

trait Cases
{
    public function toPascalCase(): Str
    {
        $this->haystack = implode(array_map(fn ($str) => ucfirst($str), explode("_", $this->haystack)));

        return $this;
    }

    public function toSnakeCase(): Str
    {
        $this->haystack = implode("_", array_map(fn ($str) => strtolower($str), preg_split('/(?=[A-Z])/', $this->haystack, -1, PREG_SPLIT_NO_EMPTY)));

        return $this;
    }
}
