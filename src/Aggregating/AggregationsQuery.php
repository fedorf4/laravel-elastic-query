<?php

namespace Ensi\LaravelElasticQuery\Aggregating;

use Closure;
use Ensi\LaravelElasticQuery\Concerns\ConstructsAggregations;
use Ensi\LaravelElasticQuery\Contracts\AggregationsBuilder;
use Ensi\LaravelElasticQuery\Contracts\SearchIndex;
use Ensi\LaravelElasticQuery\Filtering\BoolQueryBuilder;
use GuzzleHttp\Ring\Future\FutureArray;
use Illuminate\Support\Collection;

class AggregationsQuery implements AggregationsBuilder
{
    use ConstructsAggregations;

    public function __construct(protected SearchIndex $index)
    {
        $this->aggregations = new AggregationCollection();
        $this->boolQuery = new BoolQueryBuilder();
    }

    public function composite(Closure $callback): static
    {
        /** @var AggregationCollection $aggs */
        $aggs = tap($this->createCompositeBuilder(), $callback)->build();

        $this->aggregations->merge($aggs);

        return $this;
    }

    public function get(?callable $async = null): Collection|FutureArray
    {
        if ($this->aggregations->isEmpty()) {
            return new Collection();
        }

        if ($async) {
            /** @var FutureArray $promise */
            $promise = $this->execute(async: true);

            $promise->then(function (array $response) use ($async) {
                $async($this->responseToResults($response));
            });

            return $promise;
        } else {
            $response = $this->execute(async: false);

            return $this->responseToResults($response);
        }
    }

    protected function execute(bool $async = false): array|FutureArray
    {
        $dsl = [
            'size' => 0,
            'track_total_hits' => false,
            'query' => $this->boolQuery->toDSL(),
            'aggs' => $this->aggregations->toDSL(),
        ];

        return $async ? $this->index->searchAsync($dsl) : $this->index->search($dsl);
    }

    protected function responseToResults($response): Collection
    {
        return $this->aggregations->parseResults($response['aggregations'] ?? []);
    }
}
