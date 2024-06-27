<?php

namespace Ensi\LaravelElasticQuery\Tests\IntegrationTests\Search\TestCases;

use Ensi\LaravelElasticQuery\Search\SearchQuery;
use Ensi\LaravelElasticQuery\Tests\IntegrationTestCase;

class SearchIntegrationTestCase extends IntegrationTestCase
{
    protected function assertDocumentIds(SearchQuery $query, array $expected): void
    {
        $actual = $query->get()
            ->pluck('_id')
            ->all();

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    protected function assertDocumentOrder(SearchQuery $query, array $ids): void
    {
        $actual = $query->get()
            ->pluck('_id')
            ->all();

        $this->assertEquals($ids, $actual);
    }
}
