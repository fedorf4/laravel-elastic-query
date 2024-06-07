<?php

namespace Ensi\LaravelElasticQuery\Search\Enums;

class BoundaryScanner
{
    public const CHARS = 'chars';
    public const SENTENCE = 'sentence';
    public const WORD = 'word';

    public static function cases(): array
    {
        return [
            self::CHARS,
            self::SENTENCE,
            self::WORD,
        ];
    }
}
