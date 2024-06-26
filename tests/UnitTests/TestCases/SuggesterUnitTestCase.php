<?php

namespace Ensi\LaravelElasticQuery\Tests\UnitTests\TestCases;

use Ensi\LaravelElasticQuery\Suggesting\Request\Suggester;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;
use Mockery\MockInterface;

class SuggesterUnitTestCase extends UnitTestCase
{
    protected string $inner_sug_name = 'suggest';

    protected Suggester|MockInterface $mockSuggester;

    protected function fillMockSuggester(?string $name = null): Suggester|MockInterface
    {
        $this->mockSuggester = $this->mockSuggester();
        $this->mockSuggester->allows('name')->andReturn($name ?: $this->inner_sug_name);

        return $this->mockSuggester;
    }
}
