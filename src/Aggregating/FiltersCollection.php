<?php

namespace Ensi\LaravelElasticQuery\Aggregating;

use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Contracts\DSLAware;
use Illuminate\Support\Collection;

class FiltersCollection implements DSLAware
{
    private Collection $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    public function count(): int
    {
        return $this->items->count();
    }

    public function toDSL(): array
    {
        return $this->items
            ->mapWithKeys(fn (Criteria $criteria, string $key) => [$key => $criteria->toDSL()])
            ->all();
    }

    public function add(string $name, Criteria $criteria): void
    {
        $this->items->put($name, $criteria);
    }
}
