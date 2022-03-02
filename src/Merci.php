<?php

namespace Merciall\Merci;

use Illuminate\Support\Traits\Macroable;

const BASE_FILENAME = "Service.php";

/**
 * God class
 */
class Merci
{
    use Macroable;

    public function trim(?string $string = null): ?string
    {
        if (!$string) return null;
        return trim($string);
    }

    public function normal_case_to_snake_case(string ...$strings): string
    {
        $string = implode(" ", $strings);
        return strtolower(str_replace([" ", "/"], "_", $string));
    }

    public static function pascal_case_to_snake_case(string  ...$strings): string
    {
        $string = implode("", $strings);
        return implode("_", array_map(fn ($str) => strtolower($str), preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY)));
    }

    public static function snake_case_to_pascal_case(string $string)
    {
        return implode(array_map(fn ($str) => ucfirst($str), explode("_", $string)));
    }

    public static function class_name(object|string $class)
    {
        if (is_object($class)) $class = get_class($class);
        return last(explode("\\", $class));
    }

    public static function insert_to_string(string $insert, string|array $needles, string $haystack, ?string $append = ""): string
    {
        $string = str_replace("\n", " ", $haystack);
        if (is_string($needles)) $needles = [$needles];
        foreach ($needles as $needle) {
            $needle = trim($needle);
            if (!str_contains($haystack, $needle))
                continue;
            $explode = explode($needle, $string);
            $string = implode($insert . $needle, $explode);
        }
        return $string;
    }

    /**
     * Turn a structured string into an array of items
     *
     * @param string|array $start_delimiter Used to denote the start of an item (ex. "[")
     * @param string|array $end_delimiter Used to denote the end of an item (ex. "]")
     * @param string $string String being converted to an array
     * @param string|null $key_delimter String used to split items into key and value pairs (ex. ":")
     * @param boolean $key_is_reversed
     * @return array
     */
    public static function strtoarray(string|array $start_delimiter, string|array $end_delimiter, string $string, string $key_delimter = null, bool $key_is_reversed = false, array $group_by = null, string $sub_group_key = null, array $sub_group_values = null): array
    {
        $array = [];
        $items = explode($start_delimiter, $string);
        $sub_group_name = null;
        $sub_groups = [];
        for ($i = 1; $i < count($items); $i++) {
            $item = self::remove_from_end_of_string([$end_delimiter . ",", $end_delimiter], $items[$i]);
            if (!is_null($key_delimter)) {
                $delimiter = "\../";
                $pos = strpos((string) $item, $key_delimter);
                if ($pos !== false) {
                    $item = substr_replace((string) $item, $delimiter, $pos, strlen(":"));
                }
                $item = self::explode($delimiter, (string) $item);
                $key = trim($item[0]);
                $value = trim($item[1]);
                if ($key_is_reversed) {
                    $key = trim($item[1]);
                    $value = trim($item[0]);
                }
                if ($group_by) {
                    if (!in_array($key, $group_by)) {
                        if ($sub_group_key) {
                            if ($key == $sub_group_key) {
                                $sub_group_name = strtolower($sub_group_key) . 's';
                                $sub_groups[] = $value;
                                $array[key(array_slice($array, -1))][$sub_group_name][$value] = [];
                                continue;
                            }
                            if (in_array($key, $sub_group_values)) {
                                $sub_group = end($sub_groups);
                                $array[key(array_slice($array, -1))][$sub_group_name][$sub_group][$key] = $value;
                                continue;
                            }
                        }
                        $array[key(array_slice($array, -1))][$key] = $value;
                        continue;
                    }
                    $array[$key] = [
                        'value' => $value
                    ];
                    continue;
                }
                $array[$key] = $value;
                continue;
            }
            $array[] = self::remove_from_end_of_string([$end_delimiter . ",", $end_delimiter], $items[$i]);
        }
        return $array;
    }

    public static function str_contains(string $haystack, string|array $needles): bool
    {
        if (is_string($needles)) $needles = [$needles];

        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) return true;
        }

        return false;
    }

    public static function str_starts_with(string $haystack, string|array $needles): bool
    {
        if (is_string($needles)) $needles = [$needles];

        foreach ($needles as $needle) {
            if (str_starts_with($haystack, $needle)) return true;
        }

        return false;
    }

    public static function str_ends_with(string $haystack, string|array $needles): bool
    {
        if (is_string($needles)) $needles = [$needles];

        foreach ($needles as $needle) {
            if (str_ends_with($haystack, $needle)) return true;
        }

        return false;
    }

    /**
     * Undocumented function
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public static function contains(string $haystack, string $needle): bool
    {
        if (is_string($haystack)) {
            return strpos(strtolower($haystack), strtolower($needle)) !== false;
        }
    }

    /**
     * Undocumented function
     *
     * @param string|array $needles
     * @param string $haystack
     */
    public static function remove_from_start_of_string(string|array $needles, string $haystack): string
    {
        // if (!str_contains($haystack, $needles)) return $haystack;
        if (is_string($needles)) $needles = [$needles];
        foreach ($needles as $needle) {
            if (str_starts_with($haystack, $needle))
                return explode($needle, $haystack)[1];
        }
        return $haystack;
    }

    /**
     * Undocumented function
     *
     * @param string|array $needles
     * @param string $haystack
     */
    public static function remove_from_end_of_string(string|array $needles, string $haystack): string
    {
        // if (!str_contains($haystack, $needles)) return $haystack;
        if (is_string($needles)) $needles = [$needles];
        foreach ($needles as $needle) {
            if (str_ends_with($haystack, $needle))
                return explode($needle, $haystack)[0];
        }
        return $haystack;
    }

    /**
     * Undocumented function
     *
     * @param string|array $needles
     * @param string $haystack
     */
    public static function explode(string|array $needles, string $haystack, int $limit = null): ?array
    {
        $haystack = trim($haystack);

        if (is_string($needles)) $needles = [$needles];

        foreach ($needles as $needle) {
            $needle = trim($needle);

            if (!str_contains($haystack, $needle))
                continue;

            if (!$limit)
                return explode($needle, $haystack);

            return explode($needle, $haystack, $limit);
        }

        return null;
    }

    /**
     * Undocumented function
     *
     * @param string|array $needles
     * @param string $haystack
     */
    public static function explode_by_str_end(string|array $needles, string $haystack, int $limit = null): ?array
    {
        $haystack = trim($haystack);
        if (is_string($needles)) $needles = [$needles];
        foreach ($needles as $needle) {
            $needle = trim($needle);
            if (!str_ends_with($haystack, $needle))
                continue;
            return [explode($needle, $haystack)[0], $needle];
        }
        return null;
    }

    /**
     * Undocumented function
     *
     * @param string|array $needles
     * @param string $haystack
     */
    public static function explode_by_str_start(string|array $needles, string $haystack, int $limit = null): ?array
    {
        $haystack = trim($haystack);
        if (is_string($needles)) $needles = [$needles];
        foreach ($needles as $needle) {
            $needle = trim($needle);
            if (!str_starts_with($haystack, $needle))
                continue;
            return [$needle, explode($needle, $haystack)[1]];
        }
        return null;
    }


    public static function isEven($number)
    {
        return $number % 2 == 0;
    }
}
