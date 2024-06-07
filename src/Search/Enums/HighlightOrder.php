<?php

namespace Ensi\LaravelElasticQuery\Search\Enums;

class HighlightOrder
{
    public const NONE = 'none';
    public const SCORE = 'score';

    public static function cases(): array
    {
        return [
            self::NONE,
            self::SCORE,
        ];
    }
}
