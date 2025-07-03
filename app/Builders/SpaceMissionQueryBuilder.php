<?php

namespace App\Builders;

use App\Models\SpaceMission;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class SpaceMissionQueryBuilder
{
    private Builder $query;

    // public function __construct()
    // {
    //     $this->query = SpaceMission::query();
    // }
    // ============================================================================================
    // Propio para el scopeFiltered del SpaceMission
    // ============================================================================================
    public function __construct(?Builder $builder = null)
    {
        $this->query = $builder ?? SpaceMission::query();
    }

    public function applyFilters(Request $request): self
    {
        $this->filterByDestination($request->destination)
            ->filterByStatus($request->status)
            ->filterByMissionType($request->mission_type)
            ->filterByBudgetRange($request->budget_millions_min, $request->budget_millions_max);

        return $this;
    }

    public function filterByDestination(?string $destination): self
    {
        $this->query->when($destination, function ($query, $destination) {
            $query->where('destination', 'like', "%{$destination}%");
        });

        return $this;
    }

    public function filterByStatus(?string $status): self
    {
        $this->query->when($status, function ($query, $status) {
            $query->where('status', $status);
        });

        return $this;
    }

    public function filterByMissionType(?string $missionType): self
    {
        $this->query->when($missionType, function ($query, $missionType) {
            $query->where('mission_type', $missionType);
        });

        return $this;
    }

    public function filterByBudgetRange(?float $minBudget, ?float $maxBudget): self
    {
        $this->query->when($minBudget, function ($query, $minBudget) {
            $query->where('budget_millions', '>=', $minBudget);
        })->when($maxBudget, function ($query, $maxBudget) {
            $query->where('budget_millions', '<=', $maxBudget);
        });

        return $this;
    }

    public function orderByLaunchDate(string $direction = 'desc'): self
    {
        $this->query->orderBy('launch_date', $direction);

        return $this;
    }

    public function paginate(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator|array
    {
        return $this->query->paginate($perPage);
    }

    public function get(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->query->get();
    }

    // ============================================================================================
    // Propio para el scopeFiltered del SpaceMission
    // ============================================================================================
    public function getQueryBuilder(): Builder
    {
        return $this->query;
    }
}
