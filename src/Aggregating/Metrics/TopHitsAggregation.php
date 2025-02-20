<?php

namespace Ensi\LaravelElasticQuery\Aggregating\Metrics;

use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Ensi\LaravelElasticQuery\Search\Sorting\SortCollection;
use stdClass;
use Webmozart\Assert\Assert;

class TopHitsAggregation implements Aggregation
{
    public function __construct(
        private string $name,
        private ?int $size = null,
        protected ?SortCollection $sort = null,
    ) {
        Assert::stringNotEmpty(trim($name));
        Assert::nullOrGreaterThan($this->size, 0);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function parseResults(array $response): array
    {
        return [$this->name => $response[$this->name]['hits']['hits'] ?? []];
    }

    public function toDSL(): array
    {
        $body = [];

        if ($this->size !== null) {
            $body['size'] = $this->size;
        }

        if ($this->sort) {
            $body['sort'] = $this->sort->toDSL();
        }

        return [
            $this->name => [
                'top_hits' => empty($body) ? new stdClass() : $body,
            ],
        ];
    }
}
