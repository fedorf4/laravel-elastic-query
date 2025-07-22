<?php

namespace Ensi\LaravelElasticQuery\Contracts;

class FunctionScoreScript implements DSLAware
{
    public function __construct(
        protected string $source,
        protected array $params = [],
    ) {
    }

    public function toDSL(): array
    {
        return ['script' => array_filter([
            'source' => $this->source,
            'params' => $this->params,
        ])];
    }
}
