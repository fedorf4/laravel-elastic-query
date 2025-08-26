<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Closure;
use Ensi\LaravelElasticQuery\Aggregating\Range;

interface AggregationsBuilder extends BoolQuery
{
    public function terms(string $name, string $field, ?int $size = null): static;

    public function minmax(string $name, string $field): static;

    /**
     * @param string $name
     * @param string $field
     * @param Range[] $ranges
     * @return $this
     */
    public function ranges(string $name, string $field, array $ranges): static;

    public function min(string $name, string $field, mixed $missing = null): static;

    public function max(string $name, string $field, mixed $missing = null): static;

    public function count(string $path, string $field): static;

    public function script(string $name, string $aggregationType, string $source, array $params = [], string $lang = ScriptLang::PAINLESS): static;

    public function nested(string $path, Closure $callback): static;

    public function reverseNested(string $name, Closure $callback): static
}
