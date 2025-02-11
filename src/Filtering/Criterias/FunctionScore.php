<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Contracts\FunctionScoreOptions;
use Ensi\LaravelElasticQuery\Filtering\Criterias\FunctionScoreItem;
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
        private ?FunctionScoreOptions $options = null,
    ) {
        array_map(fn ($function) => Assert::isInstanceOfAny($function, [FunctionScoreItem::class]), $functions);
    }

    public function toDSL(): array
    {
        $body = [
            'query' => ['match_all' => new stdClass()],
            'functions' => array_map(fn (FunctionScoreItem $function) => $function->toDSL(), $this->functions),
        ];

        if ($this->options) {
            $body = array_merge($this->options->toArray(), $body);
        }

        return ['function_score' => $body];
    }
}
