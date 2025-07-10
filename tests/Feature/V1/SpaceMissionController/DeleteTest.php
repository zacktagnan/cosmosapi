<?php

namespace Tests\Feature\V1\SpaceMissionController;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Tests\Helpers\Traits\DataForTesting;

#[Group('api:v1')]
#[Group('api:v1:feat')]
#[Group('api:v1:feat:space_missions')]
#[Group('api:v1:feat:space_missions:delete')]
class DeleteTest extends SpaceMissionTestCase
{
    use DataForTesting;

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:success')]
    public function it_deletes_space_mission_successfully(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission();

        // Act
        $response = $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $spaceMission))
            ->assertNoContent();

        $this->assertDatabaseMissing($this->table, [
            'id' => $spaceMission->id,
        ]);

        $this->assertDatabaseCount($this->table, 0);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:not_be_found')]
    public function it_cannot_be_found_the_space_mission_to_delete(): void
    {
        $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', 999))
            ->assertNotFound();
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:with_active_status')]
    public function it_deletes_mission_with_active_status(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'name' => 'Active Space Mission to Delete',
            'status' => 'active',
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $spaceMission));

        // Assert
        $response->assertNoContent();

        $this->assertDatabaseMissing($this->table, [
            'id' => $spaceMission->id,
            'status' => 'active',
        ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:with_completed_status')]
    public function it_deletes_mission_with_completed_status(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'name' => 'Completed Space Mission to Delete',
            'status' => 'completed',
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $spaceMission));

        // Assert
        $response->assertNoContent();

        $this->assertDatabaseMissing($this->table, [
            'id' => $spaceMission->id,
            'status' => 'completed',
        ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:with_large_budget')]
    public function it_handles_deletion_of_mission_with_large_budget(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission([
            'name' => 'Expensive Space Mission',
            'budget_millions' => 999999.99,
        ]);

        // Act
        $response = $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $spaceMission));

        // Assert
        $response->assertStatus(204);

        $this->assertDatabaseMissing($this->table, [
            'id' => $spaceMission->id,
            'budget_millions' => 999999.99,
        ]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:multiple')]
    public function it_handles_multiple_deletions_correctly(): void
    {
        // Arrange
        $this->actingAs($this->user);
        $spaceMission1 = $this->createSpaceMission();
        $spaceMission2 = $this->createSpaceMission();
        $spaceMission3 = $this->createSpaceMission();

        // Act
        $response1 = $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $spaceMission1));
        $response3 = $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $spaceMission3));

        // Assert
        $response1->assertNoContent();
        $response3->assertNoContent();

        $this->assertDatabaseMissing($this->table, ['id' => $spaceMission1->id]);
        $this->assertDatabaseHas($this->table, ['id' => $spaceMission2->id]); // Should still exist
        $this->assertDatabaseMissing($this->table, ['id' => $spaceMission3->id]);
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:middleware')]
    public function it_does_not_wrap_delete_response_with_middleware(): void
    {
        // Arrange
        $spaceMission = $this->createSpaceMission();

        // Act
        $response = $this
            ->withToken($this->token)
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $spaceMission));

        // Assert
        $response->assertNoContent();

        // Ensure no JSON structure is returned for 204 responses
        $this->assertEmpty($response->getContent());
    }

    #[Test]
    #[Group('api:v1:feat:space_missions:delete:unauthenticated')]
    public function it_requires_authentication_to_delete_an_space_mission(): void
    {
        $this
            ->deleteJson(route($this->spaceMissionsBaseRouteName . 'destroy', $this->createSpaceMission()))
            ->assertUnauthorized();
    }
}
