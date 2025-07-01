<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpaceMission>
 */
class SpaceMissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $missionPrefixes = ['Apollo', 'Artemis', 'Orion', 'Voyager', 'Pioneer', 'Cassini', 'Galileo', 'Hubble', 'Kepler', 'Phoenix'];
        $destinations = ['Mars', 'Moon', 'Europa', 'Titan', 'Asteroid Belt', 'Jupiter', 'Saturn', 'Alpha Centauri', 'Space Station', 'Earth Orbit'];
        $agencies = ['NASA', 'ESA', 'SpaceX', 'Roscosmos', 'JAXA', 'CNSA', 'ISRO', 'Blue Origin'];
        $statuses = ['planned', 'active', 'completed', 'failed', 'cancelled'];
        $missionTypes = ['exploration', 'research', 'colonization', 'mining', 'rescue', 'maintenance'];

        return [
            'name' => fake()->randomElement($missionPrefixes) . ' ' . fake()->numberBetween(1, 99),
            'destination' => fake()->randomElement($destinations),
            'description' => fake()->paragraph(3),
            'launch_date' => fake()->dateTimeBetween('-2 years', '+2 years'),
            'duration_days' => fake()->numberBetween(30, 2555), // de 1 mes a 7 años
            'status' => fake()->randomElement($statuses),
            'agency' => fake()->randomElement($agencies),
            'crew_size' => fake()->numberBetween(0, 12),
            'mission_type' => fake()->randomElement($missionTypes),
            'budget_millions' => fake()->randomFloat(2, 50, 15000),
        ];
    }
}
