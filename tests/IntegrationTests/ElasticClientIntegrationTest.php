<?php

use Ensi\LaravelElasticQuery\ElasticClient;
use Ensi\LaravelElasticQuery\Tests\IntegrationTestCase;
use Ensi\LaravelElasticQuery\Tests\Models\ProductsIndex;

use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertGreaterThanOrEqual;

uses(IntegrationTestCase::class);

test('elastic client cat indices', function () {
    /** @var IntegrationTestCase $this */

    /** @var ElasticClient $client */
    $client = resolve(ElasticClient::class);

    $response = $client->catIndices(ProductsIndex::fullName());

    assertGreaterThanOrEqual(1, count($response));
    assertArrayStructure([
        [
            'index',
            'status',
            'health',
            'uuid',
            'pri',
            'rep',
            'docs.count',
            'docs.deleted',
            'store.size',
            'pri.store.size',
        ],
    ], $response);
});

test('elastic client cat indices only specified fields', function () {
    /** @var IntegrationTestCase $this */

    /** @var ElasticClient $client */
    $client = resolve(ElasticClient::class);

    $response = $client->catIndices(ProductsIndex::fullName(), ['index', 'status']);

    assertArrayStructure([['index', 'status']], $response);
    assertArrayNotHasKey('health', $response[0]);
});
