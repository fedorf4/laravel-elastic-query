<?php

namespace Ensi\LaravelElasticQuery\Search\Enums;

class Encoder
{
    public const DEFAULT = 'default';
    public const HTML = 'html';

    public static function cases(): array
    {
        return [
            self::DEFAULT,
            self::HTML,
        ];
    }
}
