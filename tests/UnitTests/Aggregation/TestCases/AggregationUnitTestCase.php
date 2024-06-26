<?php

namespace Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\TestCases;

use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;
use Mockery\MockInterface;

class AggregationUnitTestCase extends UnitTestCase
{
    protected string $agg_name = 'agg_filter';
    protected string $inner_agg_name = 'agg_inner';

    protected Aggregation|MockInterface $mockAggregation;
    protected Criteria|MockInterface $mockCriteria;

    protected function fillMockAggregation(?string $name = null): Aggregation|MockInterface
    {
        $this->mockAggregation = $this->mockAggregation();
        $this->mockAggregation->allows('name')->andReturn($name ?: $this->inner_agg_name);

        return $this->mockAggregation;
    }

    protected function fillMockCriteria(): void
    {
        $this->mockCriteria = $this->mockCriteria();
    }
}
