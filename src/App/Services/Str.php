<?php

namespace Merciall\Merci\App\Services;

use App\Classes\Merci\Exceptions\TypeError;
use Merciall\Merci\App\Services\Str\Cases;
use Merciall\Merci\App\Services\Str\Chop;
use Merciall\Merci\App\Services\Str\Comparison;
use Merciall\Merci\App\Services\Str\HandleBatchOperations;
use Merciall\Merci\App\Services\Str\HandleStandardOperations;
use Merciall\Merci\App\Services\Str\Manipulate;
use Merciall\Merci\App\Services\Str\Normalize;
use Merciall\Merci\App\Services\Str\Replace;

class Str extends Service
{
    use HandleStandardOperations, HandleBatchOperations, Normalize, Chop, Comparison, Cases, Replace, Manipulate;

    protected ?string $default;

    protected bool $return;

    protected array $matches;

    protected null|array|object $results;

    public function __construct(
        protected ?string $haystack,
        bool $standardize = true
    ) {
        if (!$standardize) {
            $this->haystack = $haystack;

            return;
        }

        $this->haystack = $this->standardize($haystack);
    }

    public function __toString(): string
    {
        return $this->haystack ?? "";
    }

    public function dd()
    {
        dd($this);
    }

    public function __invoke(bool $trim = false, bool $ucwords = false): ?string
    {
        $string = $this->haystack;

        if ($trim)
            $string = trim($string);

        if ($ucwords)
            $string = ucwords($string);

        return $string;
    }

    protected function __trim(?string $string): ?string
    {
        if (!$string)
            return null;

        return trim($string);
    }

    protected function __explode(?string $needle, ?string $haystack, ?int $limit = null): ?array
    {
        if (!$haystack)
            return null;

        $haystack = $this->__trim($haystack);

        if (!$limit)
            return explode($needle, $haystack);

        return explode($needle, $haystack, $limit);
    }

    private function _set(string $string)
    {
        $this->haystack = $string;
    }

    public function isNull(): bool
    {
        return !$this->haystack;
    }

    public function getResult(string|int $key)
    {
        if (is_int($key))
            return $this->results[$key];

        return $this->results->{$key};
    }

    public function getResultWhere(string $key, string $value, $returnProperty): string
    {
        return collect($this->results)->where($key, $value)->first()->{$returnProperty};
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setTo(string $string): Str
    {
        $this->haystack = $string;

        return $this;
    }

    public function setToResult(string|int $key): Str
    {
        if (is_int($key)) {
            $this->haystack = $this->results[$key];
        } else {
            $this->haystack = $this->results->{$key};
        }

        return $this;
    }

    private function map(array $result, array $array)
    {
        $obj = (object) [];

        foreach ($array as $key => $item)
            $obj->{$item} = $result[$key];

        return $obj;
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

    private function single_needle(string $needle, string $operation)
    {
        if ($operation($this->haystack, $this->standardize($needle))) {
            if (!$needle)
                return true;

            return $needle;
        }

        return false;
    }

    private function many_needles(array $needles, string $operation)
    {
        $hit = false;

        foreach ($needles as $needle) {
            if (!$operation($this->haystack, $this->standardize($needle)))
                continue;

            $hit = $needle;

            if (!$this->return)
                return true;

            return $needle;
        }

        if (!$this->return)
            return false;

        return $hit;
    }

    protected function standardize(?string $string): ?string
    {
        if (!$string) return null;

        $string = trim($string);

        $string = strtolower($string);

        return $string;
    }

    public function endsWith(string|array $needles): bool
    {
        if (is_string($needles))
            return str_ends_with($this->haystack, $needles);

        foreach ($needles as $needle)
            if (str_ends_with($this->haystack, $needle))
                return true;

        return false;
    }
}
