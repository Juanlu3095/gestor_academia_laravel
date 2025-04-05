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
                "nombre_nuevo" => "Manuel",
                'apellidos_nuevo' =>'Pérez Palacios',
                'email_nuevo' => 'mperez@gmail.com',
                'dni_nuevo' => '99543753Y'
            ])
        );
    }
}
