<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Ensi\LaravelElasticQuery\Search\Highlight\Highlight;

interface HighlightingQuery extends BoolQuery
{
    public function highlight(Highlight $highlight): static;
}
