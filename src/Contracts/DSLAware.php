<?php

namespace Ensi\LaravelElasticQuery\Contracts;

use stdClass;

interface DSLAware
{
    public function toDSL(): array|stdClass;
}
