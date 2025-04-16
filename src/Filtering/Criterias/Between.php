<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Webmozart\Assert\Assert;

class Between implements Criteria
{
    public function __construct(private string $field, private mixed $from, private mixed $to)
    {
        Assert::stringNotEmpty(trim($field));
    }

    public function toDSL(): array
    {
        return ['range' => [$this->field => ['gte' => $this->from, 'lt' => $this->to]]];
    }
}
