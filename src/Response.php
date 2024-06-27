<?php

namespace Ensi\LaravelElasticQuery;

use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;

class Response
{
    public static function fn(mixed $response, callable $fn): mixed
    {
        if ($response instanceof Promise) {
            return $response->then(fn ($response) => $fn($response));
        }

        return $fn($response);
    }

    public static function array(Elasticsearch|Promise $response): array|Promise
    {
        return static::fn($response, fn (Elasticsearch $response) => $response->asArray());
    }

    public static function void(Elasticsearch|Promise $response): ?Promise
    {
        return static::fn($response, fn (Elasticsearch $response) => null);
    }

    public static function bool(Elasticsearch|Promise $response): bool|Promise
    {
        return static::fn($response, fn (Elasticsearch $response) => $response->asBool());
    }
}
