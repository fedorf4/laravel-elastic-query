<?php

namespace Ensi\LaravelElasticQuery\Search\Enums;

class SearchType
{
    public const QUERY_THEN_FETCH = 'query_then_fetch';
    public const DFS_QUERY_THEN_FETCH = 'dfs_query_then_fetch';

    public static function cases(): array
    {
        return [
            self::QUERY_THEN_FETCH,
            self::DFS_QUERY_THEN_FETCH,
        ];
    }
}
