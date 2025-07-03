<?php

namespace App\Filters\SpaceMission;

use App\Filters\QueryStringFilter;
use Illuminate\Database\Eloquent\Builder;

class Destination extends QueryStringFilter
{
    protected function apply(Builder $builder): Builder
    {
        return $builder->where('destination', 'like', '%' . request()->query($this->filterName()) . '%');
    }

    protected function filterName(): string
    {
        return 'destination';
    }
}
