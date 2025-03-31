<?php

namespace Database\Seeders;

use App\Models\Incidence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Incidence::createIncidence([
            'titulo' => 'Test Incidencia',
            'sumario' => 'Sumario del test de incidencia',
            'fecha' => '2025-03-28',
            'documento' => null,
            'persona' => 1,
            'rol' => 'Alumno'
        ]);
    }
}
