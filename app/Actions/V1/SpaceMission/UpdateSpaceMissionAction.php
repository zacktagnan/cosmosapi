<?php

namespace App\Actions\V1\SpaceMission;

use App\Models\SpaceMission;

class UpdateSpaceMissionAction
{
    public function execute(SpaceMission $spaceMission, array $data): SpaceMission
    {
        $spaceMission->update($data);

        return $spaceMission->fresh();
    }
}
