<?php

use Ensi\LaravelElasticQuery\Contracts\SortableQuery;
use Ensi\LaravelElasticQuery\Search\Sorting\SortBuilder;
use Ensi\LaravelElasticQuery\Search\Sorting\SortCollection;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;

uses(UnitTestCase::class);

test('builder sort sortBy', function () {
    /** @var UnitTestCase $this */

    $sorts = new SortCollection();
    (new SortBuilder($sorts))
        ->sortBy('code', 'desc')
        ->sortBy('name');

    assertArrayStructure([['code'], ['name']], $sorts->toDSL());
});

test('builder sort sortByCustomArray', function () {
    /** @var UnitTestCase $this */

    $sorts = new SortCollection();
    (new SortBuilder($sorts))->sortByCustomArray('product_id', [2, 3, 1]);

    assertArrayStructure([['_script' => ['type', 'script', 'order']]], $sorts->toDSL());
});

test('builder sort sortByNested', function () {
    /** @var UnitTestCase $this */

    $sorts = new SortCollection();
    (new SortBuilder($sorts))
        ->sortByNested('offers', fn (SortableQuery $query) => $query->sortBy('price'))
        ->sortByNested('properties', fn (SortableQuery $query) => $query->sortBy('code'));

    assertArrayStructure(
        [['offers.price' => ['nested']], ['properties.code' => ['nested']]],
        $sorts->toDSL()
    );
});

test('builder sort sortByNested multi level', function () {
    /** @var UnitTestCase $this */

    $sorts = new SortCollection();
    (new SortBuilder($sorts))->sortByNested('offers', function (SortableQuery $query) {
        $query->sortByNested(
            'stocks',
            fn (SortableQuery $inner) => $inner->sortBy('stock')
        );
    });

    assertArrayStructure(
        [['offers.stocks.stock' => ['nested']]],
        $sorts->toDSL()
    );
});

test('builder sort sortByNested with filter', function () {
    /** @var UnitTestCase $this */

    $sorts = new SortCollection();
    (new SortBuilder($sorts))->sortByNested('offers', function (SortableQuery $query) {
        $query->sortByNested(
            'stocks',
            fn (SortableQuery $inner) => $inner->where('store_id', 150)->sortBy('stock')
        );
    });

    assertArrayFragment(
        ['offers.stocks.store_id' => 150],
        $sorts->toDSL()
    );
});
