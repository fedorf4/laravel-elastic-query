<?php

namespace Ensi\LaravelElasticQuery\Concerns;

use Closure;
use Ensi\LaravelElasticQuery\Aggregating\AggregationCollection;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\FilterAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\FiltersAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\NestedAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\ReverseNestedAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\TermsAggregation;
use Ensi\LaravelElasticQuery\Aggregating\CompositeAggregationBuilder;
use Ensi\LaravelElasticQuery\Aggregating\FiltersCollection;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\CardinalityAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\MaxAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\MinAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\MinMaxAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\RangesAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\ScriptAggregation;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\ValueCountAggregation;
use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Contracts\ScriptLang;
use Ensi\LaravelElasticQuery\Filtering\BoolQueryBuilder;
use Ensi\LaravelElasticQuery\Search\Sorting\Sort;
use Ensi\LaravelElasticQuery\Search\Sorting\SortCollection;

trait ConstructsAggregations
{
    use SupportsPath;
    use DecoratesBoolQuery;

    protected AggregationCollection $aggregations;
    protected BoolQueryBuilder $boolQuery;

    public function terms(
        string $name,
        string $field,
        ?int $size = null,
        Sort|SortCollection|null $sort = null,
        Aggregation|AggregationCollection|null $composite = null,
    ): static {
        $this->aggregations->add(new TermsAggregation($name, $this->absolutePath($field), $size, $sort, $composite));

        return $this;
    }

    public function filter(string $name, Criteria $criteria, AggregationCollection $children): static
    {
        $this->aggregations->add(new FilterAggregation($name, $criteria, $children));

        return $this;
    }

    public function filters(
        string $name,
        FiltersCollection $filters,
        Aggregation|AggregationCollection|null $composite = null,
        ?string $otherBucketKey = null,
    ): static {
        $this->aggregations->add(new FiltersAggregation($name, $filters, $composite, $otherBucketKey));

        return $this;
    }

    public function minmax(string $name, string $field): static
    {
        $this->aggregations->add(new MinMaxAggregation($name, $this->absolutePath($field)));

        return $this;
    }

    public function min(string $name, string $field, mixed $missing = null): static
    {
        $this->aggregations->add(new MinAggregation($name, $this->absolutePath($field), $missing));

        return $this;
    }

    public function max(string $name, string $field, mixed $missing = null): static
    {
        $this->aggregations->add(new MaxAggregation($name, $this->absolutePath($field), $missing));

        return $this;
    }

    public function script(
        string $name,
        string $aggregationType,
        string $source,
        array $params = [],
        string $lang = ScriptLang::PAINLESS
    ): static {
        $this->aggregations->add(new ScriptAggregation($name, $aggregationType, $source, $params, $lang));

        return $this;
    }

    public function count(string $name, string $field): static
    {
        $this->aggregations->add(new ValueCountAggregation($name, $this->absolutePath($field)));

        return $this;
    }

    public function ranges(string $name, string $field, array $ranges): static
    {
        $this->aggregations->add(new RangesAggregation($name, $this->absolutePath($field), $ranges));

        return $this;
    }

    public function cardinality(string $name, string $field): static
    {
        $this->aggregations->add(new CardinalityAggregation($name, $this->absolutePath($field)));

        return $this;
    }

    public function nested(string $path, Closure $callback): static
    {
        $name = $this->aggregations->generateUniqueName($this->name());
        $builder = $this->createCompositeBuilder("{$name}_filter", $path);

        /** @var AggregationCollection $aggs */
        $aggs = tap($builder, $callback)->build();

        if (!$aggs->isEmpty()) {
            $nested = new NestedAggregation($name, $path, $aggs);
            $this->aggregations->merge(AggregationCollection::fromAggregation($nested));
        }

        return $this;
    }

    protected function name(): string
    {
        return '';
    }

    protected function boolQuery(): BoolQueryBuilder
    {
        return $this->boolQuery;
    }

    protected function createCompositeBuilder(?string $name = null, string $path = ''): CompositeAggregationBuilder
    {
        return new CompositeAggregationBuilder(
            $name ?? $this->aggregations->generateUniqueName(),
            $this->absolutePath($path)
        );
    }
}
