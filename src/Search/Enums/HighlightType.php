<?php

namespace Ensi\LaravelElasticQuery\Search\Enums;

class HighlightType
{
    public const UNIFIED = 'unified';
    public const PLAIN = 'plain';
    public const FVH = 'fvh';

    public static function cases(): array
    {
        return [
            self::UNIFIED,
            self::PLAIN,
            self::FVH,
        ];
    }
}
