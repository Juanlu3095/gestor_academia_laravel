<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Prepares database for testing.
     */
    public function test_set_database_config()
    {
        Artisan::call('migrate:reset'); // Para asegurarnos de que la base de datos de testing está vacía
        Artisan::call('migrate');
        User::create([
            'name' => 'Pepe',
            'email' => 'pepe@gmail.com',
            'password' => 'pepe'
        ]);

        $response = $this->get('/login'); // Llamamos a esta ruta para comprobar que el sistema funciona
        $response->assertStatus(200); // Respuesta esperada
    }

    public function test_get_login_form()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Bienvenido/a'); // Comprobar que este texto salga en la página
    }

    public function test_get_register_form()
    {
        $response = $this->get('/registro');
        $response->assertStatus(200);
        $response->assertSee('Regístrese');
    }

    public function test_login()
    {
        $credentials = [
            "email" => "pepe@gmail.com",
            "password" => "pepe"
        ];

        $response = $this->post('/authenticate', $credentials); // Mandamos solicitud POST con las credenciales a la ruta indicada
        $this->assertCredentials($credentials); // Comprobamos que las credenciales son válidas
        $response->assertRedirectToRoute('welcome'); // Comprobamos que nos redirije a la ruta de name welcome si las credenciales son válidas
    }

    public function test_login_not_valid()
    {
        $credentials = [
            "email" => "pepe@gmail.com",
            "password" => "pepo"
        ];

        $response = $this->post('/authenticate', $credentials);
        $this->assertInvalidCredentials($credentials);
        $response->assertStatus(401);
    }

    public function test_register()
    {
        $user = [
            'name' => 'Alfonso',
            'email' => 'alfonsorobles@gmail.com',
            'password' => 'alfonso',
            'password_confirmation' => 'alfonso'
        ];

        $response = $this->post('/register', $user);
        $response->assertRedirect('/login'); // Comprobamos que nos redirije a la URL indicada el registro es existoso
        $this->assertCredentials($user);
    }

}
