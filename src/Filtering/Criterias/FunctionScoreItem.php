<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;

class FunctionScoreItem implements Criteria
{
    public function __construct(
        private int $weight,
        private Criteria $filter,
    ) {
    }

    public function toDSL(): array
    {
        return [
            'weight' => $this->weight,
            'filter' => $this->filter->toDSL(),
        ];
    }
}
