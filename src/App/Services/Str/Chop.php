<?php

namespace Merciall\Merci\App\Services\Str;

use Merciall\Merci\App\Services\Str;

const DETAG_START_DELIM = "<";

const DETAG_END_DELIM = ">";

trait Chop
{
    /**
     * Removes the string before the first or last occurrence of a needle
     *
     * @param string $needle The string to search for
     * @param int $occurence_number Times needle should occur before chopping
     * @return Str
     */
    public function chopBefore(string $needle, bool $last_occurrence = false, int $occurence_number = null): Str
    {
        $chops = explode($needle, $this->haystack);

        if (!$occurence_number)
            $string = array_shift($chops);

        if ($occurence_number)
            $string = $chops[$occurence_number - 1];

        if ($last_occurrence)
            $string = last($chops);

        $this->_set($string);

        return $this;
    }

    /**
     * Remove the part of the string that comes after a given string
     *
     * @param string $needle
     * @return Str
     */
    public function chopAfter(string $needle, bool $after_last_occurrence = false): Str
    {
        return $this;
    }

    /**
     * Remove the part of the string that comes between two given strings
     *
     * @param string $beginning_needle
     * @param string $ending_needle
     * @param boolean $remove_delimiters
     * @return Str
     */
    public function chopBetween(string $beginning_needle, string $ending_needle, bool $remove_delimiters = false): Str
    {
        $string = $this->haystack;

        if (!str_contains($string, $beginning_needle) || !str_contains($string, $ending_needle) ||  $string == "")
            return $this;

        $part_one = $this->__explode($beginning_needle, $string, 2);

        $part_two = $this->__explode($ending_needle, $part_one[1], 2);

        $new_string = $part_one[0] . $beginning_needle . $ending_needle . $part_two[1];

        if ($remove_delimiters)
            $new_string = $part_one[0] . $part_two[1];

        $this->_set($new_string);

        return $this;
    }

    /**
     * Keep the part of the string that comes between two given strings and remove everything else
     *
     * @param string $beginning_needle
     * @param string $ending_needle
     * @return Str
     */
    public function keepBetween(string $beginning_needle, string $ending_needle): Str
    {
    }

    /**
     * Remove HTML tags and notes from string
     *
     * @return Str
     */
    public function stripTags(): Str
    {
        $count = substr_count($this->haystack, DETAG_START_DELIM);

        for ($x = 0; $x < $count; $x++)
            $this->chopBetween(DETAG_START_DELIM, DETAG_END_DELIM, true);

        return $this;
    }
}
