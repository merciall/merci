<?php

namespace Merciall\Merci\App\Services\Str;

use Merciall\Merci\App\Services\Str;

trait HandleStandardOperations
{
    public function trim(bool $returnResult = false): Str
    {
        $string = trim($this->haystack);

        if ($returnResult)
            return $string;

        $this->haystack = $this->__trim($this->haystack);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string|array $needles
     * @param integer|null $limit
     * @param array|null $map
     * @return array|object|null
     */
    public function explode(string|array $needles, int $limit = null, array $map = null, bool $returnResults = false, bool $append = false, array $forEach = null): array|object|null
    {
        if (is_string($needles)) $needles = [$needles];

        $results = null;

        foreach ($needles as $needle) {
            $needle = strtolower(trim($needle));


            $found = false;

            if ($forEach) {
                $sub_needles = [];

                foreach ($forEach as $try) {
                    $try = strtolower($try);

                    $sub_needles[] = "$needle " . trim($try);
                }

                $sub_needles[] = $needle;

                $contains = $this->contains($sub_needles, return: true);

                if ($contains) {
                    $needle = $contains;

                    $found = true;
                }
            }

            if (!str_contains($this->haystack, $needle) || ($forEach && !$found))
                continue;

            $array = $this->__explode($needle, $this->haystack, $limit);

            if ($append)
                $array[0] = $array[0] . $needle;

            break;
        }

        if ($map) {
            $this->results = $this->map($array, $map);
        } else {
            $this->results = $array;
        }

        if ($returnResults)
            return $this->results;

        return $this;
    }

    public function explodeByArray(array $needles): Str
    {
        $delim = "||";

        $string = $this->insert_to_string(
            insert: "||",
            needles: $needles,
            haystack: $this->haystack
        );

        $this->results = array_slice(explode($delim, $string), 1);

        return $this;
    }

    private function insert_to_string(string $insert, string|array $needles, string $haystack): string
    {
        $string = str_replace("\n", " ", $haystack);

        if (is_string($needles)) $needles = [$needles];

        foreach ($needles as $needle) {
            $needle = $this->standardize($needle);

            if (!str_contains($haystack, $needle))
                continue;

            $explode = explode($needle, $string);

            $string = implode($insert . $needle, $explode);
        }

        return $string;
    }
}
