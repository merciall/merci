<?php

namespace Merciall\Merci\App\Services\Str;

use Merciall\Merci\App\Services\Str;

trait Manipulate
{
    public function toLower(): Str
    {
        $this->_set(strtolower($this->haystack));

        return $this;
    }

    public function toUpper(): Str
    {
        $this->_set(strtoupper($this->haystack));

        return $this;
    }

    public function toUpperFirst(): Str
    {
        $this->_set(ucfirst($this->haystack));

        return $this;
    }
}
