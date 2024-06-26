<?php

namespace Ensi\LaravelElasticQuery\Tests;

use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\ElasticQueryServiceProvider;
use Ensi\LaravelElasticQuery\Suggesting\Request\Suggester;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ElasticQueryServiceProvider::class,
        ];
    }

    protected function mockAggregation(): Aggregation|MockInterface
    {
        return $this->mock(Aggregation::class);
    }

    protected function mockCriteria(): Criteria|MockInterface
    {
        return $this->mock(Criteria::class);
    }

    protected function mockSuggester(): Suggester|MockInterface
    {
        return $this->mock(Suggester::class);
    }
}
