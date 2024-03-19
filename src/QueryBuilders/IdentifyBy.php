<?php

namespace Ludo237\Toolbox\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

readonly class IdentifyBy
{
    public function __construct(private string|int $key)
    {
    }

    /**
     * @phpstan-param \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model> $builder
     *
     * @phpstan-return \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model>
     */
    public function __invoke(Builder $builder): Builder
    {
        return $builder
            ->where('id', '=', $this->key)
            ->orWhere('uid', '=', $this->key);
    }
}
