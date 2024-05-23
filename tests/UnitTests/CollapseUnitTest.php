<?php

use Ensi\LaravelElasticQuery\Search\Collapsing\Collapse;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;

use function PHPUnit\Framework\assertEquals;

uses(UnitTestCase::class);

test('collapse field only', function () {
    /** @var UnitTestCase $this */

    assertEquals(['field' => 'product_id'], (new Collapse('product_id'))->toDSL());
});
