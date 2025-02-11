<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Illuminate\Contracts\Support\Arrayable;

class MoreLikeOptions implements Arrayable
{
    public function __construct(private array $options = [])
    {
    }

    public static function make(
        ?int $maxQueryTerms = null,
        ?int $minTermFreq = null,
        ?int $minDocFreq = null,
        ?int $maxDocFreq = null,
        ?int $minWordLength = null,
        ?int $maxWordLength = null,
        ?array $stopWords = null,
        ?string $analyzer = null,
        ?string $minimumShouldMatch = null,
    ): static {

        return new static(array_filter([
            'max_query_terms' => $maxQueryTerms,
            'min_term_freq' => $minTermFreq,
            'min_doc_freq' => $minDocFreq,
            'max_doc_freq' => $maxDocFreq,
            'min_word_length' => $minWordLength,
            'max_word_length' => $maxWordLength,
            'stop_words' => $stopWords,
            'analyzer' => $analyzer,
            'minimum_should_match' => $minimumShouldMatch,
        ]));
    }

    public function toArray(): array
    {
        return $this->options;
    }
}
