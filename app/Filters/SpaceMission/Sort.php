<?php

namespace App\Filters\SpaceMission;

use App\Filters\QueryStringFilter;
use Illuminate\Database\Eloquent\Builder;

final class Sort extends QueryStringFilter
{
    protected array $allowedColumns = [
        'id',
        'name',
        'launch_date',
        'budget_millions',
        'crew_size',
    ];

    protected function apply(Builder $builder): Builder
    {
        $column = request()->query('sort_by', 'id');
        $direction = strtolower(request()->query($this->filterName(), 'asc'));

        if (!in_array($column, $this->allowedColumns)) {
            return $builder; // ignorar si no es una columna válida
        }

        return $builder->orderBy($column, $direction === 'desc' ? 'desc' : 'asc');
    }

    protected function filterName(): string
    {
        return 'sort';
    }
}
