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
                'nombre' => 'Desarrollo de aplicaciones con Java.',
                'fecha' => 'Marzo 2025',
                'horas' => 300,
                'descripcion' => 'Curso avanzado de desarrollo de aplicaciones multiplataforma con Java.',
                'profesor' => 1
            ])
        );
    }
}
