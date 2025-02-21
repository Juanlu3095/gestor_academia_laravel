<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teacher::createTeacher(
            new Request([
                "nombre" => "Manuel",
                'apellidos' =>'Pérez Palacios',
                'email' => 'mperez@gmail.com',
                'dni' => '222222222A'
            ])
        );
    }
}
