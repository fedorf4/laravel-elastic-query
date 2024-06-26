<?php

use Ensi\LaravelElasticQuery\Aggregating\AggregationCollection;
use Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\TestCases\AggregationUnitTestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;

uses(AggregationUnitTestCase::class);

test('collection aggregation to DSL', function () {
    /** @var AggregationUnitTestCase $this */

    assertEquals([], (new AggregationCollection())->toDSL());
});

test('collection aggregation construct from aggregation', function () {
    /** @var AggregationUnitTestCase $this */
    $this->fillMockAggregation();

    $testing = AggregationCollection::fromAggregation($this->mockAggregation);

    assertEquals(1, $testing->count());
    assertFalse($testing->isEmpty());
});

test('collection aggregation add', function () {
    /** @var AggregationUnitTestCase $this */
    $this->fillMockAggregation();

    $expected = [$this->inner_agg_name => 'body'];

    $this->mockAggregation->allows('toDSL')->andReturn($expected);

    $testing = new AggregationCollection();
    $testing->add($this->mockAggregation);

    $this->assertEquals($expected, $testing->toDSL());
});

test('collection aggregation add already existing name', function () {
    /** @var AggregationUnitTestCase $this */
    $this->fillMockAggregation();

    $testing = new AggregationCollection();

    $testing->add($this->mockAggregation);
    $testing->add($this->mockAggregation);

})->expectException(InvalidArgumentException::class);

test('collection aggregation generate unique name', function (
    ?AggregationCollection $target,
    string $name,
    string $expected
) {
    /** @var AggregationUnitTestCase $this */

    if (!$target) {
        $this->fillMockAggregation();
        $target = AggregationCollection::fromAggregation($this->mockAggregation);
    }
    assertEquals($expected, $target->generateUniqueName($name));

})->with([
    'no items no name' => [new AggregationCollection(), ' ', 'agg_1'],
    'no items with name' => [new AggregationCollection(), 'agg_2', 'agg_2_1'],
    'has items' => [null, '', 'agg_2',],
]);

test('collection aggregation merge non unique names', function () {
    /** @var AggregationUnitTestCase $this */
    $this->fillMockAggregation();

    $source = AggregationCollection::fromAggregation($this->mockAggregation);
    $testing = AggregationCollection::fromAggregation($this->mockAggregation);

    $testing->merge($source);

})->expectException(InvalidArgumentException::class);

test('collection aggregation to DSL keeps returned names', function () {
    /** @var AggregationUnitTestCase $this */

    $testing = new AggregationCollection();

    $agg1 = $this->fillMockAggregation('agg1');
    $agg1->allows('toDSL')->andReturn(['foo' => 'body']);
    $testing->add($agg1);

    $agg2 = $this->fillMockAggregation('agg2');
    $agg2->allows('toDSL')->andReturn(['bar' => 'body']);
    $testing->add($agg2);

    assertArrayStructure(['foo', 'bar'], $testing->toDSL());
});

test('collection aggregation parse results', function () {
    /** @var AggregationUnitTestCase $this */

    $testing = new AggregationCollection();

    $agg1 = $this->fillMockAggregation('agg1');
    $agg1->allows('parseResults')->andReturn(['foo' => 'result']);
    $testing->add($agg1);

    $agg2 = $this->fillMockAggregation('agg2');
    $agg2->allows('parseResults')->andReturn(['bar' => [20]]);
    $testing->add($agg2);

    assertEquals(['foo' => 'result', 'bar' => [20]], $testing->parseResults([])->all());
});
