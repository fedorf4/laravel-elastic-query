<?php

namespace Ensi\LaravelElasticQuery\Aggregating\Bucket;

use Ensi\LaravelElasticQuery\Aggregating\AggregationCollection;
use Ensi\LaravelElasticQuery\Aggregating\BucketCollection;
use Ensi\LaravelElasticQuery\Aggregating\FiltersCollection;
use Ensi\LaravelElasticQuery\Aggregating\Result;
use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

class FiltersAggregation implements Aggregation
{
    public function __construct(
        private string $name,
        private FiltersCollection $filters,
        private Aggregation|AggregationCollection|null $composite = null,
        private ?string $otherBucketKey = null,
    ) {
        Assert::stringNotEmpty(trim($name));
        Assert::greaterThan($filters->count(), 0);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toDSL(): array
    {
        $body['filters']['filters'] = $this->filters->toDSL();

        if ($this->otherBucketKey != null) {
            $body['filters']['other_bucket_key'] = $this->otherBucketKey;
        }

        if ($this->isComposite()) {
            $body['aggs'] = $this->composite->toDSL();
        }

        return [$this->name => $body];
    }

    public function parseResults(array $response): array
    {
        $buckets = array_map(
            function (mixed $key, array $bucket) {
                $values = $this->isComposite() ? $this->composite->parseResults($bucket) : [];
                $values = $values instanceof Collection ? $values->toArray() : $values;

                return Result::parseBucketWithKey($key, $bucket, $values);
            },
            array_keys($response[$this->name]['buckets'] ?? []),
            $response[$this->name]['buckets'] ?? []
        );

        return [$this->name => new BucketCollection($buckets)];
    }

    public function isComposite(): bool
    {
        return isset($this->composite);
    }
}
