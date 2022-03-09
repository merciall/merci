<?php

namespace Merciall\Merci\App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Merciall\Merci\App\Services\Str as ServicesStr;
use Merciall\Merci\Facades\Merci;
use ValueError;

class Str implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return Merci::Str($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if (!$value instanceof ServicesStr && !is_string($value))
            throw new ValueError("The given value is not a string or a Str instance");

        return (string) $value;
    }
}
