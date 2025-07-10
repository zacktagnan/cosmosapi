<?php

namespace App\Actions\V1\SpaceMission;

use App\Models\SpaceMission;

class DeleteSpaceMissionAction
{
    public function execute(SpaceMission $spaceMission): bool
    {
        return $spaceMission->delete();
    }
}
