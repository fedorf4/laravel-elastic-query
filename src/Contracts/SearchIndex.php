<?php

namespace Ensi\LaravelElasticQuery\Contracts;

interface SearchIndex
{
    /**
     * Returns the name of attribute with unique values in index scope.
     *
     * @return string
     */
    public function tiebreaker(): string;

    /**
     * Perform search query.
     *
     * @param array $dsl
     * @param string|null $searchType
     * @return array
     */
    public function search(array $dsl, ?string $searchType = null): array;

    /**
     * Perform delete by query.
     *
     * @param array $dsl
     * @return array
     */
    public function deleteByQuery(array $dsl): array;
}
