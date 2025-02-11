<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Illuminate\Contracts\Support\Arrayable;

class MoreLikeOptions implements Arrayable
{
    public function __construct(private array $options = [])
    {
    }

    public static function make(
        ?int $minTermFreq = null,
        ?int $maxQueryTerms = null,
        ?string $minimumShouldMatch = null,
    ): static {

        return new static(array_filter([
            'min_term_freq' => $minTermFreq,
            'max_query_terms' => $maxQueryTerms,
            'minimum_should_match' => $minimumShouldMatch,
        ]));
    }

    public function toArray(): array
    {
        return $this->options;
    }
}
