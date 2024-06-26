<?php

use Ensi\LaravelElasticQuery\Contracts\BoolQuery;
use Ensi\LaravelElasticQuery\Contracts\MatchOptions;
use Ensi\LaravelElasticQuery\Contracts\MultiMatchOptions;
use Ensi\LaravelElasticQuery\Tests\Data\Models\ProductsIndex;
use Ensi\LaravelElasticQuery\Tests\IntegrationTests\Search\TestCases\SearchIntegrationTestCase;

uses(SearchIntegrationTestCase::class);

test('filtering search query where', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->where('code', 'tv');

    $this->assertDocumentIds($query, [1]);
});

test('filtering search query whereNot', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereNot('active', true);

    $this->assertDocumentIds($query, [319]);
});

test('filtering search query whereHas', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereHas('offers', function (BoolQuery $query) {
        $query->where('seller_id', 15)
            ->where('active', false);
    });

    $this->assertDocumentIds($query, [319, 405]);
});

test('filtering search query whereDoesntHave', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereDoesntHave('offers', function (BoolQuery $query) {
        $query->where('seller_id', 10)
            ->where('active', false);
    });

    $this->assertDocumentIds($query, [1, 328, 471]);
});

test('filtering search query whereNull', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereNull('package');

    $this->assertDocumentIds($query, [1, 319, 328, 471]);
});

test('filtering search query whereNotNull', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereNotNull('package');

    $this->assertDocumentIds($query, [150, 405]);
});

test('filtering search query whereMatch', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereMatch('search_name', 'black leather gloves');

    $this->assertDocumentIds($query, [319, 471]);
});

test('filtering search query whereMatch operator and', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereMatch('search_name', 'leather gloves', 'and');

    $this->assertDocumentIds($query, [319]);
});

test('filtering search query whereMatch options', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereMatch('search_name', 'leather glaves', MatchOptions::make(fuzziness: 'AUTO'));

    $this->assertDocumentIds($query, [319, 471]);
});

test('filtering search query whereMultiMatch', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereMultiMatch(['search_name', 'description'], 'nice gloves');

    $this->assertDocumentIds($query, [471, 328, 319]);
});

test('filtering search query whereMultiMatch default', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereMultiMatch([], 'nice gloves');

    $this->assertDocumentIds($query, [471, 328, 319]);
});

test('filtering search query whereMultiMatch prioritized', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereMultiMatch(['search_name^2', 'description'], 'water');

    $this->assertDocumentIds($query, [150, 405]);
});

test('filtering search query whereMultiMatch options', function () {
    /** @var SearchIntegrationTestCase $this */

    $query = ProductsIndex::query()->whereMultiMatch(
        ['search_name', 'description'],
        'nace gloves',
        MultiMatchOptions::make(fuzziness: 'AUTO')
    );

    $this->assertDocumentIds($query, [471, 328, 319]);
});
