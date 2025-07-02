<?php

namespace Tests\Feature\V1\SpaceMissionController;

use Tests\TestCase;

abstract class SpaceMissionCase extends TestCase
{
    protected string $spaceMissionsBaseRouteName;
    protected string $table;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spaceMissionsBaseRouteName = 'v1.space-missions.';
        $this->table = 'space_missions';
    }
}
