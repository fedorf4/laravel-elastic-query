<?php

namespace Ensi\LaravelElasticQuery\Tests\Data\Models;

use Ensi\LaravelElasticQuery\ElasticIndex;
use Illuminate\Support\Facades\ParallelTesting;

class ProductsIndex extends ElasticIndex
{
    protected string $name = 'test_products';

    protected string $tiebreaker = 'product_id';

    protected function indexName(): string
    {
        return $this->name . (ParallelTesting::token() ?: 0);
    }

    public static function fullName(): string
    {
        return (new static())->indexName();
    }
}
