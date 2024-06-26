<?php

use Ensi\LaravelElasticQuery\Filtering\BoolQueryBuilder;
use Ensi\LaravelElasticQuery\Search\Sorting\NestedSort;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;

use function PHPUnit\Framework\assertEquals;

uses(UnitTestCase::class);

test('nested sort to DSL', function () {
    /** @var UnitTestCase $this */

    assertEquals(
        ['path' => 'code'],
        (new NestedSort('code', new BoolQueryBuilder('', false)))->toDSL()
    );
});

test('nested sort to DSL with filter', function () {
    /** @var UnitTestCase $this */

    $filter = (new BoolQueryBuilder('offers', false))->where('seller_id', 10);

    assertArrayStructure(
        ['path', 'filter'],
        (new NestedSort('offers', $filter))->toDSL()
    );
});

test('nested sort to DSL with nested', function () {
    /** @var UnitTestCase $this */

    $nested = new NestedSort('offers.stocks', new BoolQueryBuilder('', false));

    assertArrayStructure(
        ['path', 'nested' => ['path']],
        (new NestedSort('offers', new BoolQueryBuilder('', false), $nested))->toDSL()
    );
});
