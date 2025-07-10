<?php

namespace App\Actions\V1\SpaceMission;

use App\Models\SpaceMission;

class CreateSpaceMissionAction
{
    public function execute(array $data): SpaceMission
    {
        return SpaceMission::create($data);
    }
}
