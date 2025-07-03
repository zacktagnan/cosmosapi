<?php

namespace App\Filters\SpaceMission;

use App\Filters\QueryStringFilter;
use Illuminate\Database\Eloquent\Builder;

class BudgetRange extends QueryStringFilter
{
    protected function apply(Builder $builder): Builder
    {
        $min = request()->query('budget_millions_min');
        $max = request()->query('budget_millions_max');

        return $builder
            ->when($min !== null, fn($q) => $q->where('budget_millions', '>=', (float)$min))
            ->when($max !== null, fn($q) => $q->where('budget_millions', '<=', (float)$max));
    }

    protected function filterName(): string
    {
        // Este nombre de columna no se usa directamente porque se usan 2 parámetros.
        // Así que se podría devolver como algo genérico o una cadena vacía.
        // Todo para respetar lo que exige el QueryStringFilter
        return 'budget_range';
    }
}
