<?php

use Ensi\LaravelElasticQuery\Contracts\MissingValuesMode;
use Ensi\LaravelElasticQuery\Filtering\BoolQueryBuilder;
use Ensi\LaravelElasticQuery\Search\Sorting\NestedSort;
use Ensi\LaravelElasticQuery\Search\Sorting\Sort;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;

use function PHPUnit\Framework\assertEquals;

uses(UnitTestCase::class);

test('sort field only', function () {
    /** @var UnitTestCase $this */

    assertEquals(['code' => 'asc'], (new Sort('code'))->toDSL());
});

test('sort order desc', function () {
    /** @var UnitTestCase $this */

    assertEquals(
        ['code' => 'desc'],
        (new Sort('code', 'desc'))->toDSL()
    );
});

test('sort mode', function () {
    /** @var UnitTestCase $this */

    assertEquals(
        ['code' => ['order' => 'asc', 'mode' => 'min']],
        (new Sort('code', mode: 'min'))->toDSL()
    );
});

test('sort missingValues mode', function () {
    /** @var UnitTestCase $this */

    assertEquals(
        ['code' => ['order' => 'asc', 'missing' => '_first']],
        (new Sort('code', missingValues: MissingValuesMode::FIRST))->toDSL()
    );
});

test('sort nested', function () {
    /** @var UnitTestCase $this */

    $nested = new NestedSort('offers', new BoolQueryBuilder(emptyMatchAll: false));

    assertEquals(
        ['offers.price' => ['order' => 'asc', 'nested' => ['path' => 'offers']]],
        (new Sort('offers.price', nested: $nested))->toDSL()
    );
});

test('sort invert', function () {
    /** @var UnitTestCase $this */

    $testing = new Sort('code', 'desc');

    assertEquals(['code' => ['order' => 'asc', 'missing' => '_first']], $testing->invert()->toDSL());
});

test('sort invert nested', function () {
    /** @var UnitTestCase $this */

    $testing = new Sort(
        'offers.price',
        order: 'desc',
        nested: new NestedSort('offers', new BoolQueryBuilder(emptyMatchAll: false))
    );

    assertEquals(
        $testing->toDSL(),
        $testing->invert()->invert()->toDSL()
    );
});

test('sort to string', function () {
    /** @var UnitTestCase $this */

    assertEquals('+code', (string)(new Sort('code')));
});
