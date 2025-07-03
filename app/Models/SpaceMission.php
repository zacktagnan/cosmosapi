<?php

namespace App\Models;

use App\Builders\SpaceMissionQueryBuilder;
use App\Filters\SpaceMission\BudgetRange;
use App\Filters\SpaceMission\Destination;
use App\Filters\SpaceMission\MissionType;
use App\Filters\SpaceMission\Sort;
use App\Filters\SpaceMission\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class SpaceMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'destination',
        'description',
        'launch_date',
        'duration_days',
        'status',
        'agency',
        'crew_size',
        'mission_type',
        'budget_millions'
    ];

    protected function casts(): array
    {
        return [
            'launch_date' => 'date',
            'duration_days' => 'integer',
            'crew_size' => 'integer',
            'budget_millions' => 'decimal:2',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function scopeFiltered(Builder $builder): Builder
    {
        $spaceMissionQB = new SpaceMissionQueryBuilder($builder)
            ->applyFilters(request())  // toma request() globalmente
            ->orderByLaunchDate();

        return $spaceMissionQB->getQueryBuilder();
    }

    public function scopeFilteredWithPipeline(Builder $builder): Builder
    {
        return app(Pipeline::class)
            ->send($builder)
            ->through([
                Destination::class,
                Status::class,
                MissionType::class,
                BudgetRange::class,
                Sort::class,
            ])
            ->thenReturn();
    }
}
