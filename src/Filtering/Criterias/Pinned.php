<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Contracts\DSLAware;
use stdClass;
use Webmozart\Assert\Assert;

class Pinned implements Criteria
{
    public function __construct(
        private array $ids,
        private ?DSLAware $query = null,
    ) {
        Assert::minCount($ids, 1);
    }

    public function toDSL(): array
    {
        $body = [
            'ids' => $this->ids,
            'organic' => ['match_all' => new stdClass()],
        ];

        if ($this->query) {
            $body['organic'] = $this->query->toDSL();
        }

        return ['pinned' => $body];
    }
}
