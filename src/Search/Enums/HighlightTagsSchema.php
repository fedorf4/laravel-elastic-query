<?php

namespace Ensi\LaravelElasticQuery\Search\Enums;

class HighlightTagsSchema
{
    public const STYLED = 'styled';

    public static function cases(): array
    {
        return [
            self::STYLED,
        ];
    }
}
