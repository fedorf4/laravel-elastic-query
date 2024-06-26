<?php

use Ensi\LaravelElasticQuery\Search\Cursor;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

uses(UnitTestCase::class);

test('cursor BOF', function () {
    /** @var UnitTestCase $this */

    assertTrue(Cursor::BOF()->isBOF());
});

test('cursor BOF to DSL', function () {
    /** @var UnitTestCase $this */

    assertEquals([], Cursor::BOF()->toDSL());
});

test('cursor BOF encode', function () {
    /** @var UnitTestCase $this */

    $result = Cursor::decode(Cursor::BOF()->encode());

    assertNotNull($result);
    assertTrue($result->isBOF());
});

test('cursor to DSL', function () {
    /** @var UnitTestCase $this */

    $testing = new Cursor(['+foo' => 1, '-bar' => 'string']);

    assertEquals([1, 'string'], $testing->toDSL());
});

test('cursor keys', function () {
    /** @var UnitTestCase $this */

    $testing = new Cursor(['+foo' => 1, '-bar' => 'string']);

    $this->assertEquals(['+foo', '-bar'], $testing->keys());
});

test('cursor encode', function () {
    /** @var UnitTestCase $this */

    $testing = new Cursor(['+foo' => 1, '-bar' => 'string']);

    $result = Cursor::decode($testing->encode());

    assertEquals($testing, $result);
});
