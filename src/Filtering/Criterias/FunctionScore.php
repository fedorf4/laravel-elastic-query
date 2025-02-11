<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Contracts\DSLAware;
use Ensi\LaravelElasticQuery\Contracts\FunctionScoreItem;
use Ensi\LaravelElasticQuery\Contracts\FunctionScoreOptions;
use stdClass;
use Webmozart\Assert\Assert;

class FunctionScore implements Criteria
{
    /**
     * @param array<FunctionScoreItem> $functions
     * @param FunctionScoreOptions|null $options
     */
    public function __construct(
        private array $functions,
        private ?DSLAware $query = null,
        private ?FunctionScoreOptions $options = null,
    ) {
        array_map(fn ($function) => Assert::isInstanceOfAny($function, [FunctionScoreItem::class]), $functions);
    }

    public function toDSL(): array
    {
        $body = [
            'query' => $this->query?->toDSL() ?? ['match_all' => new stdClass()],
            'functions' => array_map(fn (FunctionScoreItem $function) => $function->toArray(), $this->functions),
        ];

        if ($this->options) {
            $body = array_merge($this->options->toArray(), $body);
        }

        return ['function_score' => $body];
    }
}
