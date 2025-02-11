<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Illuminate\Contracts\Support\Arrayable;

class FunctionScoreItem implements Arrayable
{
    public function __construct(
        private int $weight,
        private Criteria $filter,
    ) {
    }

    public function toArray(): array
    {
        return [
            'weight' => $this->weight,
            'filter' => $this->filter->toDSL(),
        ];
    }
}
