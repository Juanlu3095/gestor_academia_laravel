<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::createCourse(
            new Request([
                'nombre_nuevo' => 'Desarrollo de aplicaciones con Java.',
                'fecha_nuevo' => 'Marzo 2025',
                'horas_nuevo' => 300,
                'descripcion_nuevo' => 'Curso avanzado de desarrollo de aplicaciones multiplataforma con Java.',
                'profesor_nuevo' => 1
            ])
        );
    }
}
