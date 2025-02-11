<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;

class MoreLikeThis implements Criteria
{
    private array $this = [];

    public function addId(string $id, ?string $index = null): static
    {
        $this->this[] = array_filter([
            '_id' => $id,
            '_index' => $index,
        ]);

        return $this;
    }

    public function addString(string $token): static
    {
        $this->this[] = $token;

        return $this;
    }

    public function toDSL(): array
    {
        return $this->this;
    }
}
