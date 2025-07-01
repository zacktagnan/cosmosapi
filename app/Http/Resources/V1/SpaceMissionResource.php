<?php

namespace App\Http\Resources\V1;

use App\Models\SpaceMission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SpaceMission
 */
class SpaceMissionResource extends JsonResource
{
    public static $wrap = null; // Prevents wrapping the resource in a "data" key

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'destination' => $this->destination,
            'description' => $this->description,
            'launch_date' => $this->launch_date?->format('Y-m-d'),
            'duration_days' => $this->duration_days,
            'status' => $this->status,
            'agency' => $this->agency,
            'crew_size' => $this->crew_size,
            'mission_type' => $this->mission_type,
            'budget_millions' => number_format($this->budget_millions, 2),
            'budget_millions_formatted' => '$' . number_format($this->budget_millions / 1000000, 1) . 'M',
            'is_active' => $this->isActive(),
            'is_completed' => $this->isCompleted(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString()
        ];
    }
}
