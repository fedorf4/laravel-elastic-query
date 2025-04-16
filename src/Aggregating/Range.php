<?php

namespace Ensi\LaravelElasticQuery\Aggregating;

class Range
{
    public function __construct(
        public int|float|null $from = null,
        public int|float|null $to = null,
        public ?string $key = null
    ) {
    }

    public function toDSL(): array
    {
        return array_filter(["from" => $this->from, "to" => $this->to, "key" => $this->key]);
    }
}
