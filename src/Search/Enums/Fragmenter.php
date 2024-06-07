<?php

namespace Ensi\LaravelElasticQuery\Search\Enums;

class Fragmenter
{
    public const SIMPLE = 'simple';
    public const SPAN = 'span';

    public static function cases(): array
    {
        return [
            self::SIMPLE,
            self::SPAN,
        ];
    }
}
