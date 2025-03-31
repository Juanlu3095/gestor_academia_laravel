<?php

namespace Tests\Feature;

use App\Models\Incidence;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class IncidenceTest extends TestCase
{
    /**
     * Database initial configuration.
     */
    public function test_database_config ()
    {
        Artisan::call('migrate:reset'); // Para asegurarnos de que la base de datos de testing está vacía
        Artisan::call('migrate');
        $this->assertDatabaseEmpty('incidences'); // Comprueba que existe la tabla y si está vacía
        $this->assertDatabaseEmpty('documents'); 
    }

    public function create_user()
    {
        /** @var \App\Models\User $user */ // PHPDoc para que intelephense no muestre error en $user con actingAs

       // Crear un usuario
       $user = User::factory()->makeOne();

       return $user;
    }

    /*
    * It calls Incidence and Student seeder to create an element of the table
    */
    public function test_call_seeder ()
    {
        Artisan::call('db:seed --class=StudentSeeder');
        Artisan::call('db:seed --class=IncidenceSeeder');
        $this->assertDatabaseHas('students', [
            "nombre" => "Jacinto",
            'apellidos' =>'López López',
            'email' => 'jlopezlopez@gmail.com',
            'dni' => '111111111J'
        ]);

        $this->assertDatabaseHas('incidences', [
            'titulo' => 'Test Incidencia',
            'sumario' => 'Sumario del test de incidencia',
            'fecha' => '2025-03-28',
            'document_id' => null,
            'incidenceable_id' => 1,
            'incidenceable_type' => 'Alumno'
        ]);
    }

    /**
     * Test to access the incidences page.
     */
    public function test_get_page ()
    {
       $response = $this->actingAs($this->create_user())->get('/incidencias');

       $response->assertStatus(200);
    }

    /**
     * Test to assert not valid credentials to access the incidences page.
     */
    public function test_not_valid_get_page ()
    {
        $response = $this->get('/cursos');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }

    /**
     * Test to assert creation of an incidence.
     */
    public function test_create_incidence ()
    {
        Storage::fake('fake');
        $file = UploadedFile::fake()->create('incidencias.pdf');

        $incidence = [
            'titulo' => 'Consulta médica Pepe',
            'sumario' => 'Consulta al médico de cabecera',
            'fecha' => '2025-03-27',
            'documento' => $file,
            'persona' => 2,
            'rol' => 'Alumno'
        ];

        $response = $this->actingAs($this->create_user())->post('/incidencias', $incidence);
        Storage::disk('private')->exists('incidencias.pdf'); // Comprobamos que el archivo existe en disco

        $incidence = Incidence::getIncidences($busqueda = 'Pepe')->items();
        $idDocument = $incidence[0]->document_id; // Obtenemos HEX(document_id)
        $id = hex2bin($idDocument); // Deshacemos HEX(id)

        $incidenceDB = [
            'titulo' => 'Consulta médica Pepe',
            'sumario' => 'Consulta al médico de cabecera',
            'fecha' => '2025-03-27',
            'document_id' => $id,
            'incidenceable_id' => 2,
            'incidenceable_type' => 'Alumno'
        ];
        
        $response->assertRedirectToRoute('incidencias.index'); // Una vez hecho el post nos redirige al index
        $this->assertDatabaseHas('incidences', $incidenceDB);
        $this->assertDatabaseHas('documents', ['id' => $id]); // Comprobamos que el documento esté en la tabla 'documents' con la id
        $this->assertDatabaseCount('documents', 1);
    }

    /**
    * Test to get all the incidences.
    */
    public function test_get_incidences ()
    {
        $response = $this->actingAs($this->create_user())->get('/incidencias');
        $response->assertStatus(200);
        $this->assertDatabaseCount('incidences', 2);
    }

    /**
    * Test to get a specific incidence by id.
    */
    public function test_get_incidence ()
    {
        $incidence = Incidence::getIncidences()->items(); // Obtenemos las incidencias como objetos
        $idIncidence = $incidence[0]->id; // Obtenemos la id del objeto en posicion 0
        
        $response = $this->actingAs($this->create_user())->get("/incidencias/$idIncidence");
        $response->assertStatus(200);
    }

    /**
    * Test to get a not valid incidence by id.
    */
    public function test_not_valid_get_incidence ()
    {
        $response = $this->actingAs($this->create_user())->get('/incidencias/1');
        $response->assertStatus(404);
    }

    /**
    * Test to search incidences by keyword.
    */
    public function test_search_incidence ()
    {
        $response = $this->actingAs($this->create_user())->get('/incidencias/?busqueda=consulta+médica');
        $response->assertSee('Consulta médica Pepe');
        $response->assertStatus(200);
    }

    /**
    * Test to get update a specific incidence.
    */
    public function test_update_incidence ()
    {
        $incidencebuscar = Incidence::getIncidences()->items(); // Obtenemos las incidencias como objetos
        $idIncidence = $incidencebuscar[0]->id; // Obtenemos la id

        $incidence = [
            'titulo' => 'Consulta médica Jacinto',
            'sumario' => 'Consulta de Jacinto al médico de cabecera',
            'fecha' => '2025-03-28',
            'persona' => 1,
            'rol' => 'Alumno'
        ];

        $incidenceDB = [
            'titulo' => 'Consulta médica Jacinto',
            'sumario' => 'Consulta de Jacinto al médico de cabecera',
            'fecha' => '2025-03-28',
            'incidenceable_id' => 1,
            'incidenceable_type' => 'Alumno'
        ];

        $response = $this->actingAs($this->create_user())->put("/incidencias/$idIncidence", $incidence);
        $response->assertRedirectToRoute('incidencias.index');
        $this->assertDatabaseHas('incidences', $incidenceDB);
    }

    /**
    * Test to delete a specific incidence.
    */
    public function test_delete_incidence ()
    {
        $incidence = Incidence::getIncidences()->items(); // Obtenemos las incidencias como objetos
        $idIncidence = $incidence[0]->id; // Obtenemos la id

        $response = $this->actingAs($this->create_user())->delete("/incidencias/$idIncidence");
        $this->assertDatabaseCount('incidences', 1); // Sólo hemos creado dos, debe quedar un sólo registro
        $this->assertDatabaseMissing('incidences', ['id' => $idIncidence]); // Comprobamos que ya no existe el registro con la id
    }
}
