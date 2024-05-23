<?php

use Ensi\LaravelElasticQuery\Contracts\BoolQuery;
use Ensi\LaravelElasticQuery\Contracts\SortableQuery;
use Ensi\LaravelElasticQuery\Tests\IntegrationTestCase;
use Ensi\LaravelElasticQuery\Tests\IntegrationTests\Search\TestCases\SearchIntegrationTestCase;
use Ensi\LaravelElasticQuery\Tests\Models\ProductsIndex;

use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

uses(SearchIntegrationTestCase::class);

test('search query get all', function () {
    /** @var SearchIntegrationTestCase $this */

    $results = ProductsIndex::query()->get();

    assertCount(IntegrationTestCase::TOTAL_PRODUCTS, $results);
});

test('search query get filtered', function () {
    /** @var SearchIntegrationTestCase $this */

    $results = ProductsIndex::query()
        ->where('active', true)
        ->whereDoesntHave('offers', fn (BoolQuery $query) => $query->where('seller_id', 90))
        ->get();

    assertCount(4, $results);
});

test('search query take', function () {
    /** @var SearchIntegrationTestCase $this */

    $results = ProductsIndex::query()->take(1)->get();

    assertCount(1, $results);
});

test('search query skip', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->skip(1)->take(1);

    $this->assertDocumentIds($query, [150]);
});

test('search query sortBy', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->sortBy('product_id')->take(3);

    $this->assertDocumentIds($query, [1, 150, 319]);
});

test('search query sortByCustomArray', function (array $items, array $documents) {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->sortByCustomArray('product_id', $items)->take(3);

    $this->assertDocumentIds($query, $documents);
})->with([
    'all_first' => [[150, 1, 319], [150, 1, 319]],
    'all_second' => [[319, 150, 1], [319, 150, 1]],
    'extra' => [[319, 150, 1, 328], [319, 150, 1]],
    'mixed' => [[123456789, 319, 150], [319, 150, 1]],
]);

test('search query SortByNested', function () {
    /** @var SearchIntegrationTestCase $this */

    $filter = function (BoolQuery $query) {
        $query->where('seller_id', 20)
            ->where('active', true);
    };

    $query = ProductsIndex::query()
        ->whereHas('offers', $filter)
        ->sortByNested('offers', function (SortableQuery $builder) use ($filter) {
            $filter($builder);
            $builder->sortBy('price');
        })
        ->sortBy('product_id', 'desc');

    $this->assertDocumentOrder($query, [150, 1, 328]);
});

test('search query select', function () {
    /** @var SearchIntegrationTestCase $this */

    $result = ProductsIndex::query()->select(['product_id'])->take(1)->get();

    assertEquals(1, $result[0]['_source']['product_id']);
    assertArrayNotHasKey('name', $result[0]['_source']);
});

test('search query exclude', function () {
    /** @var SearchIntegrationTestCase $this */

    $result = ProductsIndex::query()->exclude(['product_id'])->take(1)->get();

    assertArrayNotHasKey('product_id', $result[0]['_source']);
});

test('search query collapse', function () {
    /** @var SearchIntegrationTestCase $this */

    $result = ProductsIndex::query()->collapse('vat')->get();

    assertCount(2, $result);
});
