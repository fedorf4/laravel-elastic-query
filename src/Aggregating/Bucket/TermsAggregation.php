<?php

namespace Ensi\LaravelElasticQuery\Aggregating\Bucket;

use Ensi\LaravelElasticQuery\Aggregating\AggregationCollection;
use Ensi\LaravelElasticQuery\Aggregating\BucketCollection;
use Ensi\LaravelElasticQuery\Aggregating\Result;
use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Ensi\LaravelElasticQuery\Search\Sorting\Sort;
use Ensi\LaravelElasticQuery\Search\Sorting\SortCollection;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

class TermsAggregation implements Aggregation
{
    public function __construct(
        private string $name,
        private string $field,
        private ?int $size = null,
        private Sort|SortCollection|null $sort = null,
        private Aggregation|AggregationCollection|null $composite = null,
    ) {
        Assert::stringNotEmpty(trim($name));
        Assert::stringNotEmpty(trim($field));
        Assert::nullOrGreaterThan($this->size, 0);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toDSL(): array
    {
        $body = ['field' => $this->field];

        if ($this->size !== null) {
            $body['size'] = $this->size;
        }

        if ($this->sort) {
            $body['order'] = $this->sort->toDSL();
        }

        $dsl = [
            $this->name => [
                'terms' => $body,
            ],
        ];

        if ($this->isComposite()) {
            $dsl[$this->name]['aggs'] = $this->composite->toDSL();
        }

        return $dsl;
    }

    public function parseResults(array $response): array
    {
        $buckets = array_map(
            function (array $bucket) {
                $values = $this->isComposite() ? $this->composite->parseResults($bucket) : [];
                $values = $values instanceof Collection ? $values->toArray() : $values;

                return Result::parseBucket($bucket, $values);
            },
            $response[$this->name]['buckets'] ?? []
        );

        return [$this->name => new BucketCollection($buckets)];
    }

    public function isComposite(): bool
    {
        return isset($this->composite);
    }
}
