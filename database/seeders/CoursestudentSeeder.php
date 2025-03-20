<?php

namespace Database\Seeders;

use App\Models\CourseStudent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class CoursestudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseStudent::createCourseStudent(
            new Request([
                "curso" => 1, // Recordar siempre que los input a insertar coge los nombres que le digamos a createCourseStudent
                "alumno" => 1
            ])
        );
    }
}
