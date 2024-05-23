<?php

use Ensi\LaravelElasticQuery\Aggregating\CompositeAggregationBuilder;
use Ensi\LaravelElasticQuery\Contracts\AggregationsBuilder;
use Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\TestCases\AggregationUnitTestCase;

use function PHPUnit\Framework\assertTrue;

uses(AggregationUnitTestCase::class);

test('composite aggregation build empty', function () {
    /** @var AggregationUnitTestCase $this */

    assertTrue((new CompositeAggregationBuilder('root', ''))->build()->isEmpty());
});

test('composite aggregation build without filter', function () {
    /** @var AggregationUnitTestCase $this */

    assertArrayStructure(
        ['test' => ['terms']],
        (new CompositeAggregationBuilder('root', ''))->terms('test', 'test')->toDSL()
    );
});

test('composite aggregation build with filter', function () {
    /** @var AggregationUnitTestCase $this */

    assertArrayStructure(
        ['root' => ['filter', 'aggs']],
        (new CompositeAggregationBuilder('root', ''))
            ->where('code', 'tv')
            ->terms('test', 'test')
            ->toDSL()
    );
});

test('composite aggregation build with path', function () {
    /** @var AggregationUnitTestCase $this */

    $dsl = (new CompositeAggregationBuilder('root', 'offers'))
        ->where('seller_id', 15)
        ->terms('prices', 'price')
        ->toDSL();

    assertArrayFragment(['offers.seller_id' => 15], $dsl);
    assertArrayFragment(['field' => 'offers.price'], $dsl);
});

test('composite aggregation build with nested', function () {
    /** @var AggregationUnitTestCase $this */

    $dsl = (new CompositeAggregationBuilder('root'))
        ->nested('offers', function (AggregationsBuilder $builder) {
            $builder->terms('external', 'external_id');
        })
        ->toDSL();

    assertArrayStructure(['root_1' => ['nested']], $dsl);
});

test('composite aggregation build with nested with filter', function () {
    /** @var AggregationUnitTestCase $this */

    $dsl = (new CompositeAggregationBuilder('root'))
        ->nested('offers', function (AggregationsBuilder $builder) {
            $builder->where('seller_id', 15)->terms('external', 'external_id');
        })
        ->toDSL();

    $name = 'root_1';
    assertArrayStructure([$name => ['nested', 'aggs' => ["{$name}_filter" => ['filter']]]], $dsl);
});
