<?php

namespace Tests\Feature\V1\SpaceMissionController;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\DataProviders\SpaceMissionDataProvider;
use Tests\Helpers\Traits\DataForTesting;

#[Group('api:v1')]
#[Group('api:v1:feat')]
#[Group('api:v1:feat:space_missions')]
#[Group('api:v1:feat:space_missions:update')]
class UpdateTest extends SpaceMissionTestCase
{
    use DataForTesting;

    #[Test]
    #[Group('api:v1:feat:space_missions:update:success')]
    #[DataProviderExternal(SpaceMissionDataProvider::class, 'provideSpaceMissionDataToUpdate')]
    public function it_updates_space_mission_successfully(array $spaceMissionDataToUpdate): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'name' => 'Original Mission',
            'destination' => 'Mars',
            'status' => 'planned',
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->putJson(route($this->spaceMissionsBaseRouteName . 'update', $spaceMission), $spaceMissionDataToUpdate);

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
                'timestamp'
            ])
            // ->assertJson([
            //     'success' => true,
            //     'data' => [
            //         'id' => $spaceMission->id,
            //         'name' => 'Updated Mission Alpha',
            //         'destination' => 'Jupiter',
            //         'status' => 'active',
            //         'is_active' => true,
            //     ]
            // ]);
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $spaceMission->id,
                    ...$spaceMissionDataToUpdate,
                    'is_active' => true,
                ]
            ]);

        // $this->assertDatabaseHas($this->table, [
        //     'id' => $spaceMission->id,
        //     'name' => 'Updated Mission Alpha',
        //     'destination' => 'Jupiter',
        //     'status' => 'active'
        // ]);
        $this->assertDatabaseHas($this->table, array_merge(
            ['id' => $spaceMission->id],
            $spaceMissionDataToUpdate
        ));
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:update:success_partial')]
    #[DataProviderExternal(SpaceMissionDataProvider::class, 'provideSpaceMissionPartialDataToUpdate')]
    public function it_updates_partial_fields_only(array $spaceMissionPartialDataToUpdate): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'name' => 'Original Mission',
            'destination' => 'Mars',
            'budget_millions' => 5000.00
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->putJson(route($this->spaceMissionsBaseRouteName . 'update', $spaceMission), $spaceMissionPartialDataToUpdate);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $spaceMission->id,
                    'name' => $spaceMissionPartialDataToUpdate['name'],
                    'destination' => $spaceMission->destination, // Should remain unchanged
                    'budget_millions' => number_format($spaceMission->budget_millions, 2), // Should remain unchanged
                ]
            ]);

        $this->assertDatabaseHas($this->table, [
            'id' => $spaceMission->id,
            'name' => $spaceMissionPartialDataToUpdate['name'],
            'destination' => $spaceMission->destination,
            'budget_millions' => $spaceMission->budget_millions,
        ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:update:not_found')]
    public function it_returns_404_for_non_existent_mission(): void
    {
        // Arrange
        $nonExistentId = 99999;
        $updateData = [
            'name' => 'Update Non-existent'
        ];

        // Act
        $response = $this
            ->withToken($this->token)
            ->putJson(route($this->spaceMissionsBaseRouteName . 'update', $nonExistentId), $updateData);

        // Assert
        $response->assertNotFound();
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:update:validation')]
    #[DataProviderExternal(SpaceMissionDataProvider::class, 'provideInvalidSpaceMissionDataToUpdate')]
    public function it_validates_required_fields_when_updating_space_mission(array $invalidData, array $expectedErrors): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission();

        // Act
        $response = $this
            ->withToken($this->token)
            ->putJson(route($this->spaceMissionsBaseRouteName . 'update', $spaceMission), $invalidData);

        // Assert
        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expectedErrors);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:update:resource')]
    public function it_returns_updated_computed_fields(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'status' => 'planned',
            'budget_millions' => 1000.00,
        ]);

        $updateData = [
            'status' => 'completed',
            'budget_millions' => 15750.25,
        ];

        // Act
        $response = $this
            ->withToken($this->token)
            ->putJson(route($this->spaceMissionsBaseRouteName . 'update', $spaceMission), $updateData);

        // Assert
        $response->assertOk();
        $data = $response->json('data');

        $this->assertFalse($data['is_active']);
        $this->assertTrue($data['is_completed']);
        $this->assertEquals('15,750.25', $data['budget_millions']);
        $this->assertEquals('$15,750.3M', $data['budget_millions_formatted']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:update:empty_data')]
    public function it_handles_empty_update_payload(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'name' => 'Original Mission',
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->putJson(route($this->spaceMissionsBaseRouteName . 'update', $spaceMission), []);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $spaceMission->id,
                    'name' => $spaceMission->name, // Should remain unchanged
                ]
            ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:update:authentication')]
    public function it_requires_authentication_to_update_mission(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission();

        // Act
        $response = $this->putJson(route($this->spaceMissionsBaseRouteName . 'update', $spaceMission), []);

        // Assert
        $response->assertUnauthorized();
    }
}
