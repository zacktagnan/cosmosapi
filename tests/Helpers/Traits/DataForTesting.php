<?php

namespace Tests\Helpers\Traits;

use App\Models\SpaceMission;

trait DataForTesting
{
    protected function createSpaceMission(array $specificData = []): SpaceMission
    {
        return SpaceMission::factory()->create($specificData);
    }
}
