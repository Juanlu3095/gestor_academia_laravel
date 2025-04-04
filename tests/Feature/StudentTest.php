<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class StudentTest extends TestCase
{
    /**
     * Database initial configuration.
     */
    public function test_database_config ()
    {
        Artisan::call('migrate:reset'); // Para asegurarnos de que la base de datos de testing está vacía
        Artisan::call('migrate');
        $this->assertDatabaseEmpty('students'); // Comprueba que existe la tabla y si está vacía
    }

    public function create_user()
    {
        /** @var \App\Models\User $user */ // PHPDoc para que intelephense no muestre error en $user con actingAs

       // Crear un usuario
       $user = User::factory()->makeOne();

       return $user;
    }

    /**
     * Test to access the students page.
     */
    public function test_get_page ()
    {
       $response = $this->actingAs($this->create_user())->get('/alumnos');

       $response->assertStatus(200);
    }

    /**
     * Test to assert not valid credentials to access the students page.
     */
    public function test_not_valid_get_page ()
    {
        $response = $this->get('/alumnos');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }

    /**
     * Test to create a student.
     */
    public function test_create_student ()
    {
        $student = [
            'nombre_nuevo' => 'Jacinto',
            'apellidos_nuevo' => 'Contreras',
            'email_nuevo' => 'jcontreras@gmail.com',
            'dni_nuevo' => '123456789p'
        ];

        $studentDB = [
            'nombre' => 'Jacinto',
            'apellidos' => 'Contreras',
            'email' => 'jcontreras@gmail.com',
            'dni' => '123456789p'
        ];

        $response = $this->actingAs($this->create_user())->post('/alumnos', $student);
        $response->assertRedirectToRoute('alumnos.index'); // Una vez hecho el post nos redirige al index
        $this->assertDatabaseHas('students', $studentDB);
    }

    /**
    * Test to get all the students.
    */
    public function test_get_students ()
    {
        $response = $this->actingAs($this->create_user())->get('/alumnos');
        $response->assertStatus(200);
        $this->assertDatabaseCount('students', 1);
    }

    /**
    * Test to get a specific student by id.
    */
    public function test_get_student ()
    {
        $response = $this->actingAs($this->create_user())->get('/alumnos/1');
        $response->assertStatus(200);
    }

    /**
    * Test to get a not valid student by id.
    */
    public function test_not_valid_get_student ()
    {
        $response = $this->actingAs($this->create_user())->get('/alumnos/2');
        $response->assertStatus(404);
    }

    /**
    * Test to search students by keyword.
    */
    public function test_search_student ()
    {
        $response = $this->actingAs($this->create_user())->get('/alumnos/?busqueda=jacinto');
        $response->assertSee('Jacinto');
        $response->assertStatus(200);
    }

    /**
    * Test to get update a specific student.
    */
    public function test_update_student ()
    {
        $student = [
            'nombre' => 'Pepe',
            'apellidos' => 'Contreras',
            'email' => 'pcontreras@gmail.com',
            'dni' => '123456789k'
        ];

        $response = $this->actingAs($this->create_user())->put('/alumnos/1', $student);
        $response->assertStatus(200);
        $this->assertDatabaseHas('students', $student);
    }

    /**
    * Test to delete a specific student.
    */
    public function test_delete_student ()
    {
        $response = $this->actingAs($this->create_user())->delete('/alumnos/1');
        $response->assertStatus(200);
        $this->assertDatabaseEmpty('students'); // Sólo hemos creado uno, la BD debe estar vacía
    }
}
