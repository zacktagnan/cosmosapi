<?php

namespace Tests\Feature\V1\SpaceMissionController;

use App\Models\SpaceMission;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api:v1')]
#[Group('api:v1:feat')]
#[Group('api:v1:feat:space_missions')]
#[Group('api:v1:feat:space_missions:list')]
class IndexTest extends SpaceMissionTestCase
{
    #[Test]
    #[Group('api:v1:feat:space_missions:list:auth')]
    public function it_requires_authentication_to_list_missions(): void
    {
        // Arrange
        SpaceMission::factory()->create();

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index'));

        // Assert
        $response->assertUnauthorized();
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:pagination')]
    public function it_returns_paginated_missions_with_correct_format(): void
    {
        // Arrange
        // $this->actingAs($this->user);
        // o
        $this->withToken($this->token);
        $missions = SpaceMission::factory()->count(3)->create();

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index'));

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => [
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
                            'updated_at'
                        ]
                    ],
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next'
                    ],
                    'meta' => [
                        'current_page',
                        'from',
                        'last_page',
                        'path',
                        'per_page',
                        'to',
                        'total'
                    ]
                ],
                'message',
                'timestamp'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Request processed successfully'
            ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:by_destination')]
    public function it_filters_missions_by_destination(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $marsMission = SpaceMission::factory()->create(['destination' => 'Mars']);
        $jupiterMission = SpaceMission::factory()->create(['destination' => 'Jupiter']);

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index', ['destination' => 'Mars']));

        // Assert
        $response->assertOk();
        $data = $response->json('data.data');

        $this->assertCount(1, $data);
        $this->assertEquals($marsMission->id, $data[0]['id']);
        $this->assertEquals('Mars', $data[0]['destination']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:by_status')]
    public function it_filters_missions_by_status(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $activeMission = SpaceMission::factory()->create(['status' => 'active']);
        $completedMission = SpaceMission::factory()->create(['status' => 'completed']);

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index', ['status' => 'active']));

        // Assert
        $response->assertOk();
        $data = $response->json('data.data');

        $this->assertCount(1, $data);
        $this->assertEquals($activeMission->id, $data[0]['id']);
        $this->assertEquals('active', $data[0]['status']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:by_mission_type')]
    public function it_filters_missions_by_mission_type(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $explorationMission = SpaceMission::factory()->create(['mission_type' => 'exploration']);
        $researchMission = SpaceMission::factory()->create(['mission_type' => 'research']);

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index', ['mission_type' => 'exploration']));

        // Assert
        $response->assertOk();
        $data = $response->json('data.data');

        $this->assertCount(1, $data);
        $this->assertEquals($explorationMission->id, $data[0]['id']);
        $this->assertEquals('exploration', $data[0]['mission_type']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:by_budget_range')]
    public function it_filters_missions_by_budget_range(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $lowBudgetMission = SpaceMission::factory()->create(['budget_millions' => 1]);
        $highBudgetMission = SpaceMission::factory()->create(['budget_millions' => 10]);

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index', [
            'budget_millions_min' => 5,
            'budget_millions_max' => 15,
        ]));

        // Assert
        $response->assertOk();
        $data = $response->json('data.data');

        $this->assertCount(1, $data);
        $this->assertEquals($highBudgetMission->id, $data[0]['id']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:by_multiple_filters')]
    public function it_applies_multiple_filters_simultaneously(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $targetMission = SpaceMission::factory()->create([
            'destination' => 'Mars',
            'status' => 'active',
            'mission_type' => 'exploration',
            'budget_millions' => 8,
        ]);

        SpaceMission::factory()->create([
            'destination' => 'Jupiter', // Different destination
            'status' => 'active',
            'mission_type' => 'exploration',
            'budget_millions' => 8,
        ]);

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index', [
            'destination' => 'Mars',
            'status' => 'active',
            'mission_type' => 'exploration',
            'budget_millions_min' => 5,
        ]));

        // Assert
        $response->assertOk();
        $data = $response->json('data.data');

        $this->assertCount(1, $data);
        $this->assertEquals($targetMission->id, $data[0]['id']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:order_by_launch_date')]
    public function it_returns_missions_ordered_by_launch_date_desc(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $olderMission = SpaceMission::factory()->create(['launch_date' => '2023-01-01']);
        $newerMission = SpaceMission::factory()->create(['launch_date' => '2024-01-01']);

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index'));

        // Assert
        $response->assertOk();
        $data = $response->json('data.data');

        $this->assertEquals($newerMission->id, $data[0]['id']);
        $this->assertEquals($olderMission->id, $data[1]['id']);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:list:resource')]
    public function it_includes_computed_fields_in_resource(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $mission = SpaceMission::factory()->create([
            'status' => 'active',
            'budget_millions' => 15.50
        ]);

        // Act
        $response = $this->getJson(route($this->spaceMissionsBaseRouteName . 'index'));

        // Assert
        $response->assertOk();
        $data = $response->json('data.data.0');

        $this->assertTrue($data['is_active']);
        $this->assertFalse($data['is_completed']);
        $this->assertEquals('15.50', $data['budget_millions']);
        $this->assertEquals('$15.5M', $data['budget_millions_formatted']);
    }
}
