<?php

use Ensi\LaravelElasticQuery\Tests\Data\Models\ProductsIndex;
use Ensi\LaravelElasticQuery\Tests\IntegrationTestCase;

use function PHPUnit\Framework\assertEquals;

uses(IntegrationTestCase::class);

test('suggest query phrase get', function () {
    /** @var IntegrationTestCase $this */

    $query = ProductsIndex::suggest();

    $query->phrase('s', 'name.trigram')
        ->text('glves')
        ->size(1)
        ->shardSize(3);

    $results = $query->get();

    assertEquals('gloves', $results->get('s')?->first()?->options?->first()?->text);
});

test('suggest query term get', function () {
    /** @var IntegrationTestCase $this */

    $query = ProductsIndex::suggest();

    $query->term('s', 'name.trigram')
        ->text('glves')
        ->size(1)
        ->shardSize(3);

    $results = $query->get();

    assertEquals('gloves', $results->get('s')?->first()?->options?->first()?->text);
});

test('suggest query global text', function () {
    /** @var IntegrationTestCase $this */

    $query = ProductsIndex::suggest();

    $query->globalText('glves');
    $query->phrase('s', 'name.trigram')->size(1)->shardSize(3);

    $results = $query->get();

    assertEquals('gloves', $results->get('s')?->first()?->options?->first()?->text);
});
