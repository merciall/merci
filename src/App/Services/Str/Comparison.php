<?php

namespace Merciall\Merci\App\Services\Str;

use Merciall\Merci\App\Services\Str;

trait Comparison
{

    private function _equals(string $string, bool $strict = false): bool
    {
        if ($strict)
            return $this->haystack === $string;

        return $this->haystack == $string;
    }

    /**
     * Check if a string or one of an array of strings equals the haystack
     *
     * @param string|array<string> $string
     * @return boolean
     */
    public function equals(string|array $strings, bool $strict = false): bool
    {
        if (is_string($strings))
            return $this->_equals($strings, $strict);

        foreach ($strings as $string)
            if ($this->_equals($string, $strict))
                return true;

        return false;
    }
}
