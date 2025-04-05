<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class TeacherTest extends TestCase
{
    /**
     * Database initial configuration.
     */
    public function test_database_config ()
    {
        Artisan::call('migrate:reset'); // Para asegurarnos de que la base de datos de testing está vacía
        Artisan::call('migrate');
        $this->assertDatabaseEmpty('teachers'); // Comprueba que existe la tabla y si está vacía
    }

    public function create_user()
    {
        /** @var \App\Models\User $user */ // PHPDoc para que intelephense no muestre error en $user con actingAs

       // Crear un usuario
       $user = User::factory()->makeOne();

       return $user;
    }

    /**
     * Test to access the teachers page.
     */
    public function test_get_page ()
    {
       $response = $this->actingAs($this->create_user())->get('/profesores');

       $response->assertStatus(200);
    
    }

    /**
     * Test to assert not valid credentials to access the teachers page.
     */
    public function test_not_valid_get_page ()
    {
        $response = $this->get('/profesores');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }

    /**
     * Test to create a teacher.
     */
    public function test_create_teacher ()
    {
        $teacher = [
            'nombre_nuevo' => 'Jacinto',
            'apellidos_nuevo' => 'Contreras',
            'email_nuevo' => 'jcontreras@gmail.com',
            'dni_nuevo' => '79700322D'
        ];

        $teacherDB = [
            'nombre' => 'Jacinto',
            'apellidos' => 'Contreras',
            'email' => 'jcontreras@gmail.com',
            'dni' => '79700322D'
        ];

        $response = $this->actingAs($this->create_user())->post('/profesores', $teacher);
        $response->assertRedirectToRoute('profesores.index'); // Una vez hecho el post nos redirige al index
        $this->assertDatabaseHas('teachers', $teacherDB);
    }

    /**
    * Test to get all the teachers.
    */
    public function test_get_teachers ()
    {
        $response = $this->actingAs($this->create_user())->get('/profesores');
        $response->assertStatus(200);
        $this->assertDatabaseCount('teachers', 1);
    }

    /**
    * Test to get a specific teacher by id.
    */
    public function test_get_teacher ()
    {
        $response = $this->actingAs($this->create_user())->get('/profesores/1');
        $response->assertStatus(200);
    }

    /**
    * Test to get a not valid teacher by id.
    */
    public function test_not_valid_get_teacher ()
    {
        $response = $this->actingAs($this->create_user())->get('/profesores/2');
        $response->assertStatus(404);
    }

    /**
    * Test to search teachers by keyword.
    */
    public function test_search_teacher ()
    {
        $response = $this->actingAs($this->create_user())->get('/profesores/?busqueda=jacinto');
        $response->assertSee('Jacinto');
        $response->assertStatus(200);
    }

    /**
    * Test to update a specific teacher.
    */
    public function test_update_teacher ()
    {
        $teacher = [
            'nombre' => 'Pepe',
            'apellidos' => 'Contreras',
            'email' => 'pcontreras@gmail.com',
            'dni' => '53039885E'
        ];

        $response = $this->actingAs($this->create_user())->put('/profesores/1', $teacher);
        $response->assertStatus(200);
        $this->assertDatabaseHas('teachers', $teacher);
    }

    /**
    * Test to try TeacherRequest by put method.
    */
    public function test_wrong_update_teacher ()
    {
        $teacher = [
            'nombre' => 'Pepe',
            'apellidos' => 'Contreras',
            'email' => 'pcontreras@gmail.com',
        ];

        // Usamos el Accept para que salga el 422 y no la redirección que Laravel nos envía con 302 cuando falla la validación
        $headers = [
            'Accept' => 'application/json'
        ];

        $response = $this->actingAs($this->create_user())->put('/profesores/1', $teacher, $headers);
        $response->assertStatus(422);
    }

    /**
    * Test to delete a specific teacher.
    */
    public function test_delete_teacher ()
    {
        $response = $this->actingAs($this->create_user())->delete('/profesores/1');
        $response->assertStatus(200);
        $this->assertDatabaseEmpty('teachers'); // Sólo hemos creado uno, la BD debe estar vacía
    }
}
