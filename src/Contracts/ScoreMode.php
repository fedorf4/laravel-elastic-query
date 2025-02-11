<?php

namespace Ensi\LaravelElasticQuery\Contracts;

class ScoreMode
{
    public const MULTIPLY = 'multiply';
    public const SUM = 'sum';
    public const AVG = 'avg';
    public const FIRST = 'first';
    public const MAX = 'max';
    public const MIN = 'min';

    public static function cases(): array
    {
        return [
            self::MULTIPLY,
            self::SUM,
            self::AVG,
            self::FIRST,
            self::MAX,
            self::MIN,
        ];
    }
}
