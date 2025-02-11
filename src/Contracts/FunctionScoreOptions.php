<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Webmozart\Assert\Assert;

class FunctionScoreOptions implements Arrayable
{
    public function __construct(private array $options = [])
    {
    }

    public static function make(
        ?string $scoreMode = null,
        ?string $boostMode = null,
    ): static {
        Assert::oneOf($scoreMode, ScoreMode::cases());
        Assert::oneOf($boostMode, BoostMode::cases());

        return new static(array_filter([
            'score_mode' => $scoreMode,
            'boost_mode' => $boostMode,
        ]));
    }

    public function toArray(): array
    {
        return $this->options;
    }
}
