<?php

namespace Merciall\Merci\App\Services\Str;

use App\Classes\Merci\Exceptions\ReferenceError;
use Merciall\Merci\App\Services\Str;

trait HandleBatchOperations
{
    private function get_array()
    {
        if (!$this->results)
            throw new ReferenceError("There must be an array to apply an Each method to. Try running an explode method to create an array first.");

        return $this->results;
    }

    public function trimEach(): Str
    {
        $array = [];

        foreach ($this->get_array() as $key => $item)
            if (!is_null($item) && $item !== "")
                $array[$key] = $this->__trim($item);

        $this->results = $array;

        return $this;
    }

    public function explodeEach(string|array $needles, int $limit = null, bool $relate = false, array $map = null): Str
    {
        $array = [];

        foreach ($this->get_array() as $key => $item) {
            if (is_null($item) || $item == "")
                continue;

            $arr = $this->__explode($needles, $item, $limit);

            if ($relate) {
                $array[$arr[0]] = $arr[1];

                continue;
            }

            if ($map) {
                $array[$key] = $this->map($arr, $map);

                continue;
            }

            $array[$key] = $arr;
        }

        $this->results = $array;

        return $this;
    }

    public function forEach(callable $fn): Str
    {
        foreach ($this->get_array() as $key => $item)
            $this->results[$key] = $fn($item, $key);

        return $this;
    }
}
