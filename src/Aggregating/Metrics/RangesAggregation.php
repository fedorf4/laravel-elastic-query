<?php

namespace Ensi\LaravelElasticQuery\Aggregating\Metrics;

use Ensi\LaravelElasticQuery\Aggregating\Result;
use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Webmozart\Assert\Assert;

class RangesAggregation implements Aggregation
{
    protected array $ranges = [];

    public function __construct(protected string $name, protected string $field)
    {
        Assert::stringNotEmpty(trim($name));
        Assert::stringNotEmpty(trim($field));
    }

    public function name(): string
    {
        return $this->name;
    }

    public function add(int|float|null $from = null, int|float|null $to = null, ?string $key = null): void
    {
        $this->ranges[] = array_filter(["from" => $from, "to" => $to, "key" => $key]);
    }

    public function toDSL(): array
    {
        if (empty($this->ranges)) {
            return [];
        }

        return [
            $this->name => [
                "range" => [
                    "field" => $this->field,
                    "ranges" => $this->ranges,
                ]
            ]
        ];
    }

    public function parseResults(array $response): array
    {
        return array_map(
            callback: fn (array $bucket) => Result::parseBucket($bucket),
            array: $response[$this->name]['buckets'] ?? []
        );
    }
}
