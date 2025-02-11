<?php

namespace Ensi\LaravelElasticQuery\Filtering\Criterias;

use Ensi\LaravelElasticQuery\Contracts\Criteria;
use Ensi\LaravelElasticQuery\Contracts\MoreLikeOptions;
use Webmozart\Assert\Assert;

class MoreLike implements Criteria
{
    public function __construct(
        private array $fields,
        private MoreLikeThis $likeThis,
        private ?MoreLikeOptions $options = null,
    ) {
        Assert::minCount($fields, 1);
        array_map(Assert::stringNotEmpty(...), $fields);
    }

    public function toDSL(): array
    {
        $body = [
            'fields' => $this->fields,
            'like' => $this->likeThis->toDSL(),
        ];

        if ($this->options) {
            $body = array_merge($this->options->toArray(), $body);
        }

        return ['more_like_this' => $body];
    }
}
