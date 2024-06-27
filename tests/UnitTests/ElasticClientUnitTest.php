<?php

use Ensi\LaravelElasticQuery\ElasticClient;
use Ensi\LaravelElasticQuery\Tests\UnitTestCase;

use function PHPUnit\Framework\assertEquals;

uses(UnitTestCase::class);

test('elastic client resolve basic auth data', function (array $config, array $expected) {
    /** @var UnitTestCase $this */

    assertEquals($expected, ElasticClient::resolveBasicAuthData($config));
})->with([
    'separate username and password' => [
        [
            'hosts' => ['https://elastic.domain.io:9200'],
            'username' => 'foo',
            'password' => 'bar',
        ],
        ['foo', 'bar'],
    ],
    'separate username without password' => [
        [
            'hosts' => ['https://elastic.domain.io:9200'],
            'username' => 'foo',
        ],
        ['foo', ''],
    ],
    'username and password in the host' => [
        ['hosts' => ['https://elastic1.domain.io:9200', 'https://foo:bar@elastic2.domain.io:9200']],
        ['foo', 'bar'],
    ],
    'only username in the host' => [
        ['hosts' => ['https://foo@elastic1.domain.io:9200', 'https://elastic2.domain.io:9200']],
        ['foo', ''],
    ],
    'missing auth data' => [
        ['hosts' => ['https://elastic.domain.io:9200']],
        ['', ''],
    ],
]);
