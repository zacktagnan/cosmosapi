<?php

namespace App\Filters\SpaceMission;

use App\Filters\QueryStringFilter;
use Illuminate\Database\Eloquent\Builder;

class Status extends QueryStringFilter
{
    protected function apply(Builder $builder): Builder
    {
        return $builder->where('status', request()->query($this->filterName()));
    }

    protected function filterName(): string
    {
        return 'status';
    }
}
