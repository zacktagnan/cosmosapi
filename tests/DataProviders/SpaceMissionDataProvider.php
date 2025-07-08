<?php

namespace Tests\DataProviders;

class SpaceMissionDataProvider
{
    public static function provideSpaceMissionDataToCreate(): array
    {
        return [
            'space_mission_data_to_create' => [
                [
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
                ],
            ],
        ];
    }

    public static function provideInvalidSpaceMissionData(): array
    {
        return [
            'empty payload' => [
                [],
                [
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
                ],
            ],
            'launch date is past' => [
                [
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
                ],
                ['launch_date'],
            ],
            'invalid enum columns' => [
                [
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
                ],
                ['status', 'mission_type'],
            ],
            'invalid numeric columns' => [
                [
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
                ],
                ['duration_days', 'crew_size', 'budget_millions',],
            ],
        ];
    }

    public static function provideSpaceMissionDataToUpdate(): array
    {
        return [
            'club_data_to_update' => [
                [
                    'name' => 'Updated Club Name',
                    'budget' => 432000,
                ],
            ],
        ];
    }
}
