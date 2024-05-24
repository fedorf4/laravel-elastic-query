<?php

use Ensi\LaravelElasticQuery\Aggregating\Bucket;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\MinMaxScoreAggregation;
use Ensi\LaravelElasticQuery\Aggregating\MinMax;
use Ensi\LaravelElasticQuery\Contracts\AggregationsBuilder;
use Ensi\LaravelElasticQuery\Search\Sorting\Sort;
use Ensi\LaravelElasticQuery\Tests\Data\Models\ProductsIndex;
use Ensi\LaravelElasticQuery\Tests\IntegrationTestCase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertEqualsCanonicalizing;
use function PHPUnit\Framework\assertGreaterThanOrEqual;

uses(IntegrationTestCase::class);

test('aggregation query get', function () {
    /** @var IntegrationTestCase $this */

    $results = ProductsIndex::aggregate()
        ->where('package', 'bottle')
        ->terms('codes', 'code')
        ->count('product_count', 'product_id')
        ->nested(
            'offers',
            fn (AggregationsBuilder $builder) => $builder->where('seller_id', 10)->minmax('price', 'price')
        )
        ->get();

    assertEqualsCanonicalizing(
        ['voda-san-pellegrino-mineralnaya-gazirovannaya', 'water'],
        $results->get('codes')->pluck('key')->all()
    );

    assertEquals(new MinMax(168.0, 611.0), $results->get('price'));
    assertEquals(2, $results->get('product_count'));
});

test('aggregation query composite', function () {
    /** @var IntegrationTestCase $this */

    $results = ProductsIndex::aggregate()
        ->composite(function (AggregationsBuilder $builder) {
            $builder->where('package', 'bottle')
                ->terms('codes', 'code');
        })
        ->get();

    assertEqualsCanonicalizing(
        ['voda-san-pellegrino-mineralnaya-gazirovannaya', 'water'],
        $results->get('codes')->pluck('key')->all()
    );
});

test('aggregation query cardinality', function () {
    /** @var IntegrationTestCase $this */

    $results = ProductsIndex::aggregate()
        ->cardinality('cardinality', 'active')
        ->get();

    assertEquals(2, $results->get('cardinality'));
});

test('aggregation query count all', function () {
    /** @var IntegrationTestCase $this */

    $results = ProductsIndex::aggregate()
        ->count('product_count', 'product_id')
        ->get();

    assertEquals(IntegrationTestCase::TOTAL_PRODUCTS, $results->get('product_count'));
});

test('aggregation query terms size', function () {
    /** @var IntegrationTestCase $this */

    $results = ProductsIndex::aggregate()
        ->where('package', 'bottle')
        ->terms('codes', 'code', 1)
        ->get();

    assertCount(1, $results->get('codes'));
});

test('aggregation query terms with sortBy composite value', function () {
    /** @var IntegrationTestCase $this */

    $sort = new Sort('score_max');
    $composite = new MinMaxScoreAggregation();

    $results = ProductsIndex::aggregate()
        ->whereMatch('description', 'water')
        ->where('package', 'bottle')
        ->terms(
            name: 'codes',
            field: 'code',
            size: 2,
            sort: $sort,
            composite: $composite
        )
        ->get();

    $scores = $results->get('codes')->map(
        fn (Bucket $bucket) => $bucket->getCompositeValue('score')->max
    );

    assertCount(2, $results->get('codes'));
    assertGreaterThanOrEqual($scores->first(), $scores->last());
});
