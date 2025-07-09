<?php

namespace Tests\Feature\V1\SpaceMissionController;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api:v1')]
#[Group('api:v1:feat')]
#[Group('api:v1:feat:space_missions:store')]
class StoreTestSinDataProvider extends SpaceMissionTestCase
{
    #[Test]
    #[Group('api:v1:feat:space_missions:store:success')]
    public function it_creates_space_mission_successfully(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $spaceMissionData = [
            'name' => 'Test Mission Alpha',
            'destination' => 'Mars',
            'description' => 'A test mission to explore Mars surface and collect samples.',
            'launch_date' => now()->addDays(30)->format('Y-m-d'),
            'duration_days' => 365,
            'status' => 'planned',
            'agency' => 'ESA',
            'crew_size' => 6,
            'mission_type' => 'exploration',
            'budget_millions' => 5000.00,
        ];

        // Act
        $response = $this->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $spaceMissionData);

        // Assert
        $response->assertCreated()
            ->assertJsonStructure([
                'success',
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
                'message',
                'timestamp'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Test Mission Alpha',
                    'destination' => 'Mars',
                    'status' => 'planned',
                ]
            ]);

        $this->assertDatabaseHas($this->table, [
            'name' => 'Test Mission Alpha',
            'destination' => 'Mars',
        ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:store:authentication')]
    public function it_requires_authentication_to_create_mission(): void
    {
        // Arrange
        $spaceMissionData = [
            'name' => 'Unauthorized Mission',
            'destination' => 'Jupiter',
        ];

        // Act
        $response = $this->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $spaceMissionData);

        // Assert
        $response->assertUnauthorized();
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:store:general_validation')]
    public function it_validates_required_fields_when_creating_mission(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->postJson(route($this->spaceMissionsBaseRouteName . 'store'), []);

        // Assert
        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
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
            ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:store:launch_date_validation')]
    public function it_validates_launch_date_is_not_in_past(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $spaceMissionData = [
            'name' => 'Past Mission',
            'destination' => 'Mars',
            'description' => 'A mission with past launch date.',
            'launch_date' => now()->subDay()->format('Y-m-d'),
            'duration_days' => 365,
            'status' => 'planned',
            'agency' => 'ESA',
            'crew_size' => 6,
            'mission_type' => 'exploration',
            'budget_millions' => 5000.00,
        ];

        // Act
        $response = $this->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $spaceMissionData);

        // Assert
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['launch_date']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:store:enum_validation')]
    public function it_validates_enum_values(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $spaceMissionData = [
            'name' => 'Invalid Mission',
            'destination' => 'Mars',
            'description' => 'A mission with invalid enum values.',
            'launch_date' => now()->addDay()->format('Y-m-d'),
            'duration_days' => 365,
            'status' => 'invalid_status',
            'agency' => 'ESA',
            'crew_size' => 6,
            'mission_type' => 'invalid_type',
            'budget_millions' => 5000.00,
        ];

        // Act
        $response = $this->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $spaceMissionData);

        // Assert
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['status', 'mission_type']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:store:numeric_validation')]
    public function it_validates_numeric_constraints(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $spaceMissionData = [
            'name' => 'Invalid Budget Mission',
            'destination' => 'Mars',
            'description' => 'A mission with invalid numeric values.',
            'launch_date' => now()->addDay()->format('Y-m-d'),
            'duration_days' => -10,
            'status' => 'planned',
            'agency' => 'ESA',
            'crew_size' => 100,
            'mission_type' => 'exploration',
            'budget_millions' => 0.50,
        ];

        // Act
        $response = $this->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $spaceMissionData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'duration_days',
                'crew_size',
                'budget_millions',
            ]);
    }
}
