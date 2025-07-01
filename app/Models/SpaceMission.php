<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
