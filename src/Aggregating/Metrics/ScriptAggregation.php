<?php

namespace Ensi\LaravelElasticQuery\Aggregating\Metrics;

use Ensi\LaravelElasticQuery\Aggregating\Result;
use Ensi\LaravelElasticQuery\Contracts\Aggregation;
use Ensi\LaravelElasticQuery\Contracts\ScriptLang;
use Webmozart\Assert\Assert;

class ScriptAggregation implements Aggregation
{
    public function __construct(
        private readonly string $name,
        private readonly string $aggregationType,
        private readonly array  $params,
        private readonly string $source,
        private readonly string $lang = ScriptLang::PAINLESS,
    ) {
        Assert::stringNotEmpty(trim($name));
        Assert::stringNotEmpty(trim($aggregationType));
        Assert::stringNotEmpty(trim($source));
        Assert::oneOf($lang, ScriptLang::cases());
    }

    public function name(): string
    {
        return $this->name;
    }

    public function parseResults(array $response): array
    {
        return [$this->name => Result::parseValue($response[$this->name]) ?? 0];
    }

    public function toDSL(): array
    {
        $script = [
            'source' => $this->source,
            'lang' => $this->lang,
        ];

        if (!empty($this->params)) {
            $script['params'] = $this->params;
        }

        return [
            $this->name => [
                $this->aggregationType => [
                    'script' => $script,
                ],
            ],
        ];
    }
}
