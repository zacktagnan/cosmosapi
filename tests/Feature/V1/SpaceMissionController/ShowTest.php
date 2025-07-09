<?php

namespace Tests\Feature\V1\SpaceMissionController;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Tests\Helpers\Traits\DataForTesting;

#[Group('api:v1')]
#[Group('api:v1:feat')]
#[Group('api:v1:feat:space_missions')]
#[Group('api:v1:feat:space_missions:show')]
class ShowTest extends SpaceMissionTestCase
{
    use DataForTesting;

    #[Test]
    #[Group('api:v1:feat:space_missions:show:success')]
    public function it_shows_space_mission_successfully(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'name' => 'Mars Explorer',
            'destination' => 'Mars',
            'status' => 'active',
            'budget_millions' => 5000.00,
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->getJson(route($this->spaceMissionsBaseRouteName . 'show', $spaceMission));

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'destination',
                    'description',
                    'launch_date',
                    'duration_days',
                    'status',
                    'agency',
                    'crew_size',
                    'mission_type',
                    'budget_millions',
                    'budget_millions_formatted',
                    'is_active',
                    'is_completed',
                    'created_at',
                    'updated_at',
                ],
                'timestamp',
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $spaceMission->id,
                    'name' => 'Mars Explorer',
                    'destination' => 'Mars',
                    'status' => 'active',
                    'is_active' => true,
                    'is_completed' => false,
                ]
            ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:show:authentication')]
    public function it_requires_authentication_to_show_mission(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission();

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'show', $spaceMission));

        // Assert
        $response->assertUnauthorized();
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:show:not-found')]
    public function it_returns_404_for_non_existent_mission(): void
    {
        // Arrange
        $nonExistentId = 99999;

        // Act
        $response = $this
            ->withToken($this->token)
            ->getJson(route($this->spaceMissionsBaseRouteName . 'show', $nonExistentId));

        // Assert
        $response->assertNotFound();
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:show:resource_computed_fields')]
    public function it_includes_computed_fields_in_single_mission(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'status' => 'completed',
            'budget_millions' => 12500.75,
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->getJson(route($this->spaceMissionsBaseRouteName . 'show', $spaceMission));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertFalse($data['is_active']);
        $this->assertTrue($data['is_completed']);
        $this->assertEquals('12,500.75', $data['budget_millions']);
        $this->assertEquals('$12,500.8M', $data['budget_millions_formatted']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:show:resource_launch_date')]
    public function it_formats_launch_date_correctly(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'launch_date' => '2025-12-25',
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->getJson(route($this->spaceMissionsBaseRouteName . 'show', $spaceMission));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertEquals('2025-12-25', $data['launch_date']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:show:minimum_budget')]
    public function it_handles_mission_with_minimum_budget(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'budget_millions' => 1.00,
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->getJson(route($this->spaceMissionsBaseRouteName . 'show', $spaceMission));

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertEquals('1.00', $data['budget_millions']);
        $this->assertEquals('$1.0M', $data['budget_millions_formatted']);
    }
}
