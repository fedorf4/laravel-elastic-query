<?php

use Ensi\LaravelElasticQuery\Aggregating\AggregationCollection;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\NestedAggregation;
use Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\TestCases\AggregationUnitTestCase;

use function PHPUnit\Framework\assertEquals;

uses(AggregationUnitTestCase::class);

beforeEach(function () {
    /** @var AggregationUnitTestCase $this */
    $this->fillMockAggregation();
    $this->fillMockCriteria();
});

test('nested aggregation to DSL', function () {
    /** @var AggregationUnitTestCase $this */
    $this->mockAggregation->allows('toDSL')->andReturn([$this->inner_agg_name => 'body']);

    assertArrayStructure(
        [$this->agg_name => ['nested' => ['path'], 'aggs' => [$this->inner_agg_name]]],
        (new NestedAggregation($this->agg_name, 'offers', AggregationCollection::fromAggregation($this->mockAggregation)))->toDSL()
    );
});

test('nested aggregation parse results', function () {
    /** @var AggregationUnitTestCase $this */

    $expected = [$this->inner_agg_name => 'value'];
    $this->mockAggregation->allows('parseResults')->andReturn($expected);

    assertEquals(
        $expected,
        (new NestedAggregation($this->agg_name, 'offers', AggregationCollection::fromAggregation($this->mockAggregation)))
            ->parseResults([$this->agg_name => $expected])
    );
});
