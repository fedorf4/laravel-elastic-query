<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Illuminate\Contracts\Support\Arrayable;

class FunctionScoreItem implements Arrayable
{
    public function __construct(
        protected int $weight,
        protected Criteria $filter,
    ) {
    }

    public function toArray(): array
    {
        return [
            'filter' => $this->filter->toDSL(),
            'weight' => $this->weight,
        ];
    }
}
