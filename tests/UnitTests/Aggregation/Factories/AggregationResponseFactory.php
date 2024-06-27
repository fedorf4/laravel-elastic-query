<?php

namespace Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\Factories;

use Ensi\LaravelTestFactories\Factory;

class AggregationResponseFactory extends Factory
{
    protected array $items = [];

    protected function definition(): array
    {
        $items = $this->items;
        if (!$items) {
            $this->faker->randomList(function () use (&$items) {
                $items = array_merge($items, $this->generateItem());
            }, 1);
        }

        return $items;
    }

    public function addItem(?string $aggName = null, ?array $buckets = null): self
    {
        $this->items = array_merge($this->items, $this->generateItem($aggName, $buckets));

        return $this;
    }

    public function make(array $extra = []): array
    {
        return $this->makeArray($extra);
    }

    protected function generateItem(?string $aggName = null, ?array $buckets = null): array
    {
        if ($buckets === null) {
            $buckets = [['key' => 'tv', 'doc_count' => 4]];
        }

        return [$aggName ?: $this->faker->word() => [
            'doc_count_error_upper_bound' => 0,
            'buckets' => $buckets,
        ]];
    }
}
