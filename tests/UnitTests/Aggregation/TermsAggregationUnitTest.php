<?php

use Ensi\LaravelElasticQuery\Aggregating\Bucket;
use Ensi\LaravelElasticQuery\Aggregating\Bucket\TermsAggregation;
use Ensi\LaravelElasticQuery\Aggregating\BucketCollection;
use Ensi\LaravelElasticQuery\Aggregating\Metrics\MinMaxScoreAggregation;
use Ensi\LaravelElasticQuery\Aggregating\MinMax;
use Ensi\LaravelElasticQuery\Search\Sorting\Sort;
use Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\Factories\AggregationResponseFactory;
use Ensi\LaravelElasticQuery\Tests\UnitTests\Aggregation\TestCases\AggregationUnitTestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertInstanceOf;

uses(AggregationUnitTestCase::class);

test('terms aggregation to DSL', function () {
    /** @var AggregationUnitTestCase $this */

    assertArrayStructure(
        ['agg1' => ['terms' => ['field']]],
        (new TermsAggregation('agg1', 'code'))->toDSL()
    );
});

test('terms aggregation to DSL with size', function () {
    /** @var AggregationUnitTestCase $this */

    assertArrayStructure(
        ['agg1' => ['terms' => ['field', 'size']]],
        (new TermsAggregation('agg1', 'code', 24))->toDSL()
    );
});

test('terms aggregation to DSL with sort', function () {
    /** @var AggregationUnitTestCase $this */
    $orderField = 'name';

    assertArrayStructure(
        ['agg1' => ['terms' => ['field', 'order' => [$orderField]]]],
        (new TermsAggregation(
            name: 'agg1',
            field: 'code',
            sort: new Sort($orderField)
        ))->toDSL()
    );
});

test('terms aggregation to DSL with composite', function () {
    /** @var AggregationUnitTestCase $this */

    assertArrayStructure(
        [
            'agg1' => [
                'terms' => ['field'], 'aggs' => [
                    'score_min' => ['min' => ['script']],
                    'score_max' => ['max' => ['script']],
                ],
            ],
        ],
        (new TermsAggregation(
            name: 'agg1',
            field: 'code',
            composite: new MinMaxScoreAggregation()
        ))->toDSL()
    );
});

test('terms aggregation to DSL with all', function () {
    /** @var AggregationUnitTestCase $this */
    $orderField = 'name';

    assertArrayStructure(
        [
            'agg1' => [
                'terms' => ['field', 'size', 'order' => [$orderField]],
                'aggs' => [
                    'score_min' => ['min' => ['script']],
                    'score_max' => ['max' => ['script']],
                ],
            ],
        ],
        (new TermsAggregation(
            name: 'agg1',
            field: 'code',
            size: 24,
            sort: new Sort($orderField),
            composite: new MinMaxScoreAggregation()
        ))->toDSL()
    );
});

test('terms aggregation parse result success', function () {
    /** @var AggregationUnitTestCase $this */
    $aggName = 'agg1';
    $result = (new TermsAggregation($aggName, 'code'))->parseResults(
        AggregationResponseFactory::new()->addItem($aggName)->make()
    );

    assertArrayHasKey($aggName, $result);
    assertInstanceOf(BucketCollection::class, $result[$aggName]);
    assertInstanceOf(Bucket::class, $result[$aggName]->first());
});

test('terms aggregation parse result empty', function () {
    /** @var AggregationUnitTestCase $this */
    $aggName = 'agg1';
    $result = (new TermsAggregation($aggName, 'code'))->parseResults(
        AggregationResponseFactory::new()->addItem($aggName, [])->make()
    );

    assertArrayHasKey($aggName, $result);
    assertInstanceOf(BucketCollection::class, $result[$aggName]);
});

test('terms aggregation parse result with composite', function () {
    /** @var AggregationUnitTestCase $this */
    $aggName = 'agg1';
    $buckets = [
        [
            'key' => 'tv',
            'doc_count' => 4,
            'score_max' => ['value' => 2],
            'score_min' => ['value' => 1],
        ],
    ];

    $result = (new TermsAggregation(
        name: $aggName,
        field: 'code',
        composite: new MinMaxScoreAggregation()
    ))->parseResults(AggregationResponseFactory::new()->addItem($aggName, $buckets)->make());

    /** @var Bucket $bucket */
    $bucket = $result[$aggName]->first();
    /** @var MinMax $score */
    $score = $bucket->getCompositeValue('score');

    $this->assertInstanceOf(Bucket::class, $bucket);
    $this->assertEquals($buckets[0]['score_min']['value'], $score->min);
    $this->assertEquals($buckets[0]['score_max']['value'], $score->max);
});

test('terms aggregation parse result with composite empty', function () {
    /** @var AggregationUnitTestCase $this */
    $aggName = 'agg1';

    $result = (new TermsAggregation(
        name: $aggName,
        field: 'code',
        composite: new MinMaxScoreAggregation()
    ))->parseResults(AggregationResponseFactory::new()->addItem($aggName, [])->make());

    assertArrayHasKey($aggName, $result);
    assertInstanceOf(BucketCollection::class, $result[$aggName]);
});
