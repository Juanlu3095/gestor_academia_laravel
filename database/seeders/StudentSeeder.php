<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::createStudent(
            new Request([
                "nombre" => "Jacinto",
                'apellidos' =>'López López',
                'email' => 'jlopezlopez@gmail.com',
                'dni' => '111111111J'
            ])
        );
    }
}
