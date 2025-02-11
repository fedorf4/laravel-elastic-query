<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use Illuminate\Contracts\Support\Arrayable;

class MoreLikeThis implements Arrayable
{
    private array $this = [];

    public function addId(string $id, ?string $index = null): static
    {
        $this->this[] = array_filter([
            '_id' => $id,
            '_index' => $index,
        ]);

        return $this;
    }

    public function addString(string $token): static
    {
        $this->this[] = $token;

        return $this;
    }

    public function toArray(): array
    {
        return $this->this;
    }
}
