<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Contracts\DSLAware;
use Ensi\LaravelElasticQuery\Contracts\FunctionScoreItem;
use Ensi\LaravelElasticQuery\Contracts\FunctionScoreOptions;
use Ensi\LaravelElasticQuery\Contracts\FunctionScoreScript;
use stdClass;
use Webmozart\Assert\Assert;

class FunctionScore implements Criteria
{
    /**
     * @param array<FunctionScoreItem> $functions
     */
    public function __construct(
        protected ?DSLAware $query = null,
        protected ?FunctionScoreOptions $options = null,
        protected array $functions = [],
        protected ?FunctionScoreScript $scriptScore = null,
        protected ?float $weight = null,
    ) {
        Assert::allIsInstanceOfAny($functions, [FunctionScoreItem::class]);
    }

    public function toDSL(): array
    {
        $body = [
            'query' => $this->query?->toDSL() ?? ['match_all' => new stdClass()],
        ];

        if ($this->functions) {
            $body['functions'] = array_map(fn (FunctionScoreItem $function) => $function->toArray(), $this->functions);
        }

        if ($this->scriptScore) {
            $body['script_score'] = $this->scriptScore->toDSL();
        }

        if (!is_null($this->weight)) {
            $body['weight'] = $this->weight;
        }

        if ($this->options) {
            $body = array_merge($this->options->toArray(), $body);
        }

        return ['function_score' => $body];
    }
}
