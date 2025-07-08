<?php

namespace Tests\Feature\V1\SpaceMissionController;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\DataProviders\SpaceMissionDataProvider;

#[Group('api:v1')]
#[Group('api:v1:feat')]
#[Group('api:v1:feat:space_missions')]
#[Group('api:v1:feat:space_missions:store')]
class StoreTest extends SpaceMissionTestCase
{
    #[Test]
    #[Group('api:v1:feat:space_missions:store:success')]
    #[DataProviderExternal(SpaceMissionDataProvider::class, 'provideSpaceMissionDataToCreate')]
    public function it_creates_space_mission_successfully(array $spaceMissionData): void
    {
        // Arrange / Act
        $response = $this
            ->withToken($this->token)
            ->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $spaceMissionData);

        // Assert
        $response->assertCreated()
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
                    'name' => 'Test Mission Alpha',
                    'destination' => 'Mars',
                    'status' => 'planned',
                ]
            ]);

        $this->assertDatabaseHas('space_missions', [
            'name' => 'Test Mission Alpha',
            'destination' => 'Mars',
        ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:store:validation')]
    #[DataProviderExternal(SpaceMissionDataProvider::class, 'provideInvalidSpaceMissionData')]
    public function it_validates_required_fields_when_creating_mission(array $invalidData, array $expectedErrors): void
    {
        // Arrange / Act
        $response = $this
            ->withToken($this->token)
            ->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $invalidData);

        // Assert
        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expectedErrors);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:store:authentication')]
    public function it_requires_authentication_to_create_mission(): void
    {
        // Arrange
        $spaceMissionData = [];

        // Act
        $response = $this->postJson(route($this->spaceMissionsBaseRouteName . 'store'), $spaceMissionData);

        // Assert
        $response->assertUnauthorized();
    }
}
