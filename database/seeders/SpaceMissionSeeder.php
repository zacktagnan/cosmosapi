<?php

namespace Database\Seeders;

use App\Models\SpaceMission;
use Illuminate\Database\Seeder;

class SpaceMissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generar misiones adicionales usando el factory
        SpaceMission::factory(64)->create();

        // Crear algunas misiones específicas y conocidas
        SpaceMission::create([
            'name' => 'Artemis III',
            'destination' => 'Moon',
            'description' => 'Misión histórica para devolver a los humanos a la superficie lunar por primera vez desde el Apolo 17. Se centra en establecer una exploración lunar sostenible y preparar futuras misiones a Marte, incluyendo el primer alunizaje de una mujer.',
            'launch_date' => '2026-09-15',
            'duration_days' => 30,
            'status' => 'planned',
            'agency' => 'NASA',
            'crew_size' => 4,
            'mission_type' => 'exploration',
            'budget_millions' => 7500.00,
        ]);

        SpaceMission::create([
            'name' => 'Mars Colony Alpha',
            'destination' => 'Mars',
            'description' => 'Primera colonia humana permanente en Marte. Establecimiento de infraestructura crítica para futuros esfuerzos de colonización, incluyendo sistemas de soporte vital, agricultura marciana y producción de combustible local.',
            'launch_date' => '2028-07-20',
            'duration_days' => 900,
            'status' => 'planned',
            'agency' => 'SpaceX',
            'crew_size' => 8,
            'mission_type' => 'colonization',
            'budget_millions' => 12000.00,
        ]);

        SpaceMission::create([
            'name' => 'Europa Deep Explorer',
            'destination' => 'Europa',
            'description' => 'Misión robótica avanzada para perforar la corteza de hielo de Europa y explorar el océano subsuperficial en busca de signos de vida microbiana. Incluye análisis químico y biológico de muestras oceánicas.',
            'launch_date' => '2025-03-10',
            'duration_days' => 2555,
            'status' => 'active',
            'agency' => 'ESA',
            'crew_size' => 0,
            'mission_type' => 'research',
            'budget_millions' => 4200.00,
        ]);

        SpaceMission::create([
            'name' => 'Asteroid Mining Beta',
            'destination' => 'Asteroid Belt',
            'description' => 'Operación comercial de minería de asteroides enfocada en la extracción de elementos de tierras raras y agua de asteroides cercanos a la Tierra. Demostración de viabilidad económica de la minería espacial.',
            'launch_date' => '2024-11-05',
            'duration_days' => 365,
            'status' => 'completed',
            'agency' => 'Blue Origin',
            'crew_size' => 2,
            'mission_type' => 'mining',
            'budget_millions' => 850.50,
        ]);

        SpaceMission::create([
            'name' => 'Titan Atmospheric Study',
            'destination' => 'Titan',
            'description' => 'Investigación atmosférica a largo plazo del ciclo del metano en Titán y evaluación del potencial para formas de vida basadas en hidrocarburos. Incluye análisis detallado de lagos de metano y química orgánica compleja.',
            'launch_date' => '2023-08-12',
            'duration_days' => 1825,
            'status' => 'failed',
            'agency' => 'JAXA',
            'crew_size' => 0,
            'mission_type' => 'research',
            'budget_millions' => 3100.00,
        ]);
    }
}
