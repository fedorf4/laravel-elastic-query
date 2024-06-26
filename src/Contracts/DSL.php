<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use stdClass;

class DSL
{
    public static function filter(array $dsl): array|stdClass
    {
        return array_filter($dsl, fn (mixed $item) => !is_null($item)) ?: new stdClass();
    }
}
