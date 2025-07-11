<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ScribeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'scribe@example.com'],
            [
                'name' => 'Scribe Documentation User',
                'password' => bcrypt('password'),
            ]
        );

        // Crear token para documentación
        $token = $user->createToken('scribe-docs')->plainTextToken;

        $this->command->info("Token created for Scribe: {$token}");
        $this->command->info("Add this to your .env file:");
        $this->command->info("SCRIBE_AUTH_KEY='{$token}'");
    }
}
