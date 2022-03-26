<?php

namespace Merciall\Merci\App\Services\Str;

use Merciall\Merci\App\Services\Str;

trait Replace
{
    /**
     * Replace strings contained in the haystack with given strings
     *
     * @param array|string $search String or array of strings to replace
     * @param array|string $replace String or array of string to replace the search
     * @param integer $count The number of times this operation is to occur
     */
    public function replace(array|string $search, array|string $replace): Str
    {
        $this->haystack = str_replace($search, $replace, $this->haystack);

        return $this;
    }

    /**
     * Replace hinted strings contained in the haystack with object values. 
     * Hinted strings are strings contained between '[' and ']' and lead Str to a value contained inside a matched given object.
     *
     * @param array $objects
     */
    public function replaceThroughObjects(array $objects): Str
    {
        $exploded = explode("[", $this->haystack);

        $string = $this->haystack;

        foreach ($exploded as $key => $value) {
            if ($key == 0) continue;

            $hint = explode("]", $value, 2)[0];

            $props = explode(".", $hint);

            $magik = $objects[$props[0]];

            foreach ($props as $x => $prop) {
                if ($x == 0)
                    continue;

                $magik = $magik->{$prop};
            }

            $string = str_replace("[$hint]", $magik, $string);
        }

        $this->haystack = $string;

        return $this;
    }
}
