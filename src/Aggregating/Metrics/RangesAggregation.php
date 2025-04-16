<?php

namespace Ensi\LaravelElasticQuery\Aggregating\Metrics;

use Ensi\LaravelElasticQuery\Aggregating\Range;
use Ensi\LaravelElasticQuery\Aggregating\Result;
use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Webmozart\Assert\Assert;

class RangesAggregation implements Aggregation
{
    /**
     * @param string $name
     * @param string $field
     * @param Range[] $ranges
     */
    public function __construct(protected string $name, protected string $field, protected array $ranges)
    {
        Assert::stringNotEmpty(trim($name));
        Assert::stringNotEmpty(trim($field));
        Assert::allIsInstanceOf($ranges, Range::class);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function add(Range $range): self
    {
        $this->ranges[] = $range;

        return $this;
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
                    "ranges" => array_map(fn (Range $range) => $range->toDSL(), $this->ranges),
                ],
            ],
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
