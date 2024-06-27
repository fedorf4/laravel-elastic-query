<?php

use Ensi\LaravelElasticQuery\Aggregating\AggregationCollection;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\FilterAggregation;
use Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\TestCases\AggregationUnitTestCase;

use function PHPUnit\Framework\assertEquals;

uses(AggregationUnitTestCase::class);

beforeEach(function () {
    /** @var AggregationUnitTestCase $this */
    $this->fillMockAggregation();
    $this->fillMockCriteria();
});

test('filter aggregation to DSL', function () {
    /** @var AggregationUnitTestCase $this */

    $this->mockAggregation->allows('toDSL')->andReturn([$this->inner_agg_name => 'value']);
    $this->mockCriteria->allows('toDSL')->andReturn(['bool' => 'body']);

    assertArrayStructure(
        [$this->agg_name => ['filter' => ['bool'], 'aggs' => [$this->inner_agg_name]]],
        (new FilterAggregation($this->agg_name, $this->mockCriteria, AggregationCollection::fromAggregation($this->mockAggregation)))->toDSL()
    );
});

test('filter aggregation parse results', function () {
    /** @var AggregationUnitTestCase $this */

    $expected = [$this->inner_agg_name => 'value'];
    $this->mockAggregation->allows('parseResults')->andReturn($expected);

    assertEquals(
        $expected,
        (new FilterAggregation($this->agg_name, $this->mockCriteria, AggregationCollection::fromAggregation($this->mockAggregation)))
            ->parseResults([$this->agg_name => $expected])
    );
});
