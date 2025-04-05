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
                "nombre_nuevo" => "Jacinto",
                'apellidos_nuevo' =>'López López',
                'email_nuevo' => 'jlopezlopez@gmail.com',
                'dni_nuevo' => '94735930B'
            ])
        );

        Student::createStudent(
            new Request([
                "nombre_nuevo" => "Pepe",
                'apellidos_nuevo' =>'Lorente Moreno',
                'email_nuevo' => 'plorente@gmail.com',
                'dni_nuevo' => '90393207X'
            ])
        );
    }
}
