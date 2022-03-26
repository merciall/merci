<?php

namespace Merciall\Merci\App\Services\Str;

use Merciall\Merci\App\Services\Str;

/**
 * The Normalize trait provides the ability to apply a standard set of operations to all needles that enter the class
 */
trait Normalize
{
    protected $normalize = null;

    /**
     * Sets the normalize method to be applied to the haystack and all needles
     *
     * @param callable $fn
     * @return Str
     */
    public function normalize(callable $fn): Str
    {
        $this->haystack = $fn($this->haystack);

        $this->normalize = $fn;

        return $this;
    }
}
