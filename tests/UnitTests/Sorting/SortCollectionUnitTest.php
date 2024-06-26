<?php

use Ensi\LaravelElasticQuery\Search\Cursor;
use Ensi\LaravelElasticQuery\Search\Sorting\Sort;
use Ensi\LaravelElasticQuery\Search\Sorting\SortCollection;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

uses(UnitTestCase::class);

test('collection sort empty', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();

    assertTrue($collection->isEmpty());
    assertEquals([], $collection->toDSL());
});

test('collection sort to DSL', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));
    $collection->add(new Sort('bar'));

    assertEquals([['foo' => 'asc'], ['bar' => 'asc']], $collection->toDSL());
});

test('collection sort add existing field', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));
    $collection->add(new Sort('foo'));
})->expectException(InvalidArgumentException::class);

test('collection sort keys', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));
    $collection->add(new Sort('bar', 'desc'));

    assertEquals(['+foo', '-bar'], $collection->keys());
});

test('collection sort invert', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));
    $collection->add(new Sort('bar', 'desc'));

    assertEquals(['-foo', '+bar'], $collection->invert()->keys());
});

test('collection sort with tiebreaker', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));

    assertEquals(['+foo', '+bar'], $collection->withTiebreaker('bar')->keys());
});

test('collection sort with existing tiebreaker', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('code'));
    $collection->add(new Sort('id', 'desc'));
    $collection->add(new Sort('rating'));

    assertEquals(
        ['+code', '-id', '+rating'],
        $collection->withTiebreaker('id')->keys()
    );
});

test('collection sort match BOF cursor', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));
    $collection->add(new Sort('bar'));

    assertTrue($collection->matchCursor(Cursor::BOF()));
});

test('collection sort create cursor', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));
    $collection->add(new Sort('bar'));

    $cursor = $collection->createCursor(['sort' => [1, 'value']]);

    assertTrue($collection->matchCursor($cursor));
});

test('collection sort create cursor invalid sort', function () {
    /** @var UnitTestCase $this */

    $collection = new SortCollection();
    $collection->add(new Sort('foo'));
    $collection->add(new Sort('bar'));

    $collection->createCursor(['sort' => [1]]);
})->expectException(InvalidArgumentException::class);
