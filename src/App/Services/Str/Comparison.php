<?php

namespace Merciall\Merci\App\Services\Str;

use Illuminate\Support\Collection;
use Merciall\Merci\App\Services\Str;

/**
 * The comparision trait adds the ability to perform basic and advanced comparision operations between the haystack and one or more needles
 */
trait Comparison
{
    private function _equals(string $string, bool $strict = false): bool
    {
        if (is_callable($this->normalize))
            $string = ($this->normalize)($string);

        if ($strict)
            return $this->haystack === $string;

        return $this->haystack == $string;
    }

    /**
     * Check if a needle or one of an array of needles equals the haystack
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

    public function returnObjectWhereHaystackEquals(string $key, Collection $objects): bool|object
    {
        foreach ($objects as $object)
            if ($this->equals($object->{$key}))
                return $object;

        return false;
    }

    public function returnObjectWhereHaystackContains(string $key, Collection $objects): bool|object
    {
        foreach ($objects as $object)
            if ($this->contains($object->{$key}))
                return $object;

        return false;
    }


    /**
     * Undocumented function
     *
     * @param string|array<string> $needles
     * @param string|null $default
     * @param boolean $return Return the first needle contained inside the haystack
     * @return string
     */
    public function contains(string|array $needles, string $default = null, bool $return = false): bool|string
    {
        if (is_array($needles))
            $this->validate($needles)->type('str');

        $this->default = $default;

        $this->return = $return;

        if (is_string($needles))
            return $this->single_needle($needles, "str_contains");

        return $this->many_needles($needles, "str_contains");
    }
}
