<?php

namespace Ensi\LaravelElasticQuery\Tests;

use Ensi\LaravelElasticQuery\ElasticQuery;
use Ensi\LaravelElasticQuery\Tests\Seeds\ProductIndexSeeder;

class IntegrationTestCase extends TestCase
{
    public const TOTAL_PRODUCTS = 6;

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        config()->set('tests.recreate_index', env('RECREATE_INDEX', true));
    }

    protected function setUp(): void
    {
        parent::setUp();

        ProductIndexSeeder::run();
    }

    protected function tearDown(): void
    {
        ElasticQuery::disableQueryLog();

        parent::tearDown();
    }
}
