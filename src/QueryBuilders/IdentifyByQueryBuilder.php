<?php

namespace Ludo237\Toolbox\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

readonly class IdentifyByQueryBuilder
{
    public function __construct(private string|int $key)
    {
    }

    public function __invoke(Builder $builder): Builder
    {
        return $builder
            ->where('id', '=', $this->key)
            ->orWhere('uid', '=', $this->key);
    }
}
