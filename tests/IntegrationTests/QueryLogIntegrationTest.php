<?php

use Ensi\LaravelElasticQuery\ElasticQuery;
use Ensi\LaravelElasticQuery\Tests\IntegrationTestCase;
use Ensi\LaravelElasticQuery\Tests\Models\ProductsIndex;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertGreaterThan;

uses(IntegrationTestCase::class);

test('query log logging disabled', function () {
    /** @var IntegrationTestCase $this */

    ProductsIndex::query()->get();

    assertCount(0, ElasticQuery::getQueryLog());
});

test('query log logging enabled', function () {
    /** @var IntegrationTestCase $this */

    ElasticQuery::enableQueryLog();

    ProductsIndex::query()->get();

    assertCount(1, ElasticQuery::getQueryLog());
});

test('query log actual time', function () {
    /** @var IntegrationTestCase $this */

    ElasticQuery::enableQueryLog();
    $this->travel(-1)->days();

    ProductsIndex::query()->get();

    $record = ElasticQuery::getQueryLog()[0];
    assertGreaterThan(now(), $record->timestamp);
});
