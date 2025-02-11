<?php

namespace Ensi\LaravelElasticQuery\Contracts;

class BoostMode
{
    public const MULTIPLY = 'multiply';
    public const REPLACE = 'replace';
    public const SUM = 'sum';
    public const AVG = 'avg';
    public const MAX = 'max';
    public const MIN = 'min';

    public static function cases(): array
    {
        return [
            self::MULTIPLY,
            self::SUM,
            self::AVG,
            self::REPLACE,
            self::MAX,
            self::MIN,
        ];
    }
}
