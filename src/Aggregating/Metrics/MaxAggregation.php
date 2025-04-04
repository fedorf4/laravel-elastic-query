<?php

namespace Ensi\LaravelElasticQuery\Aggregating\Metrics;

use Ensi\LaravelElasticQuery\Aggregating\Result;
use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Webmozart\Assert\Assert;

class MaxAggregation implements Aggregation
{
    public function __construct(
        private readonly string $name,
        private readonly string $field,
        private readonly mixed $missing = null,
    ) {
        Assert::stringNotEmpty(trim($name));
        Assert::stringNotEmpty(trim($field));
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toDSL(): array
    {
        $body['field'] = $this->field;

        if ($this->missing) {
            $body['missing'] = $this->missing;
        }

        return [
            $this->name => ['max' => $body],
        ];
    }

    public function parseResults(array $response): array
    {
        return [$this->name => Result::parseValue($response[$this->name]) ?? 0];
    }
}
