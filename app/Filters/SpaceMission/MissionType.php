<?php

namespace App\Filters\SpaceMission;

use App\Filters\QueryStringFilter;
use Illuminate\Database\Eloquent\Builder;

class MissionType extends QueryStringFilter
{
    protected function apply(Builder $builder): Builder
    {
        return $builder->where('mission_type', request()->query($this->filterName()));
    }

    protected function filterName(): string
    {
        return 'mission_type';
    }
}
