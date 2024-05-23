<?php

use Ensi\LaravelElasticQuery\Search\Cursor;
use Ensi\LaravelElasticQuery\Tests\IntegrationTestCase;
use Ensi\LaravelElasticQuery\Tests\IntegrationTests\Search\TestCases\SearchIntegrationTestCase;
use Ensi\LaravelElasticQuery\Tests\Models\ProductsIndex;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;

uses(SearchIntegrationTestCase::class);

test('pagination search query cursor', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()
        ->sortBy('package')
        ->sortBy('rating', 'desc');

    $page1 = $query->cursorPaginate(2);

    assertEquals(Cursor::BOF()->encode(), $page1->current);
    assertNull($page1->previous);

    $page2 = $query->cursorPaginate(2, $page1->next);

    assertEquals($page1->current, $page2->previous);
});

test('pagination search query page', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()
        ->sortBy('product_id');

    $page1 = $query->paginate(2, 1);

    assertEquals(IntegrationTestCase::TOTAL_PRODUCTS, $page1->total);
    assertEquals(1, $page1->offset);
    assertEquals(2, $page1->size);
    assertCount(2, $page1->hits);
    assertEquals(150, $page1->hits[0]['_source']['product_id']);
});
