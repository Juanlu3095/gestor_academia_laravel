<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class CourseTest extends TestCase
{
    /**
     * Database initial configuration.
     */
    public function test_database_config ()
    {
        Artisan::call('migrate:reset'); // Para asegurarnos de que la base de datos de testing está vacía
        Artisan::call('migrate');
        $this->assertDatabaseEmpty('courses'); // Comprueba que existe la tabla y si está vacía
    }

    public function create_user()
    {
        /** @var \App\Models\User $user */ // PHPDoc para que intelephense no muestre error en $user con actingAs

       // Crear un usuario
       $user = User::factory()->makeOne();

       return $user;
    }

    /**
     * Database initial configuration for teachers since courses has a relationship with teachers.
     */
    public function test_prepare_teachers()
    {
        $teacher = [
            'nombre_nuevo' => 'Jacinto',
            'apellidos_nuevo' => 'Contreras',
            'email_nuevo' => 'jcontreras@gmail.com',
            'dni_nuevo' => '92239997S'
        ];

        $teacherDB = [
            'nombre' => 'Jacinto',
            'apellidos' => 'Contreras',
            'email' => 'jcontreras@gmail.com',
            'dni' => '92239997S'
        ];

        $response = $this->actingAs($this->create_user())->post('/profesores', $teacher);
        $this->assertDatabaseHas('teachers', $teacherDB);
    }

    /**
     * Database initial configuration for students since courses has a relationship with students.
     */
    public function test_prepare_students()
    {
        $student = [
            'nombre_nuevo' => 'Jaimito',
            'apellidos_nuevo' => 'Pérez',
            'email_nuevo' => 'jperez@gmail.com',
            'dni_nuevo' => '67838733A'
        ];

        $studentDB = [
            'nombre' => 'Jaimito',
            'apellidos' => 'Pérez',
            'email' => 'jperez@gmail.com',
            'dni' => '67838733A'
        ];

        $response = $this->actingAs($this->create_user())->post('/alumnos', $student);
        $this->assertDatabaseHas('students', $studentDB);
    }

    /**
     * Test to access the courses page.
     */
    public function test_get_page ()
    {
       $response = $this->actingAs($this->create_user())->get('/cursos');

       $response->assertStatus(200);
    }

    /**
     * Test to assert not valid credentials to access the courses page.
     */
    public function test_not_valid_get_page ()
    {
        $response = $this->get('/cursos');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }

    /**
     * Test to create a course.
     */
    public function test_create_course ()
    {
        $course = [
            'nombre_nuevo' => 'Desarrollo de aplicaciones con PHP y MYSQL',
            'fecha_nuevo' => 'Diciembre 2025',
            'horas_nuevo' => 300,
            'descripcion_nuevo' => 'Curso de desarrollo de aplicaciones web con PHP y MYSQL',
            'profesor_nuevo' => 1
        ];

        // En la ruta el profesor se incluye con el input 'profesor' pero para verlo en la BD es 'teacher_id'
        $courseDB = [
            'nombre' => 'Desarrollo de aplicaciones con PHP y MYSQL',
            'fecha' => 'Diciembre 2025',
            'horas' => 300,
            'descripcion' => 'Curso de desarrollo de aplicaciones web con PHP y MYSQL',
            'teacher_id' => 1
        ];

        $response = $this->actingAs($this->create_user())->post('/cursos', $course);
        $response->assertRedirectToRoute('cursos.index'); // Una vez hecho el post nos redirige al index
        $this->assertDatabaseHas('courses', $courseDB);
    }

    /**
    * Test to get all the courses.
    */
    public function test_get_courses ()
    {
        $response = $this->actingAs($this->create_user())->get('/cursos');
        $response->assertStatus(200);
        $this->assertDatabaseCount('courses', 1);
    }

    /**
    * Test to get a specific course by id.
    */
    public function test_get_course ()
    {
        $response = $this->actingAs($this->create_user())->get('/cursos/1');
        $response->assertStatus(200);
    }

    /**
    * Test to get a not valid course by id.
    */
    public function test_not_valid_get_course ()
    {
        $response = $this->actingAs($this->create_user())->get('/cursos/2');
        $response->assertStatus(404);
    }

    /**
    * Test to search courses by keyword.
    */
    public function test_search_course ()
    {
        $response = $this->actingAs($this->create_user())->get('/cursos/?busqueda=php');
        $response->assertSee('Desarrollo de aplicaciones con PHP y MYSQL');
        $response->assertStatus(200);
    }

    /**
    * Test to get update a specific course.
    */
    public function test_update_course ()
    {
        $course = [
            'nombre' => 'Desarrollo de aplicaciones con Javascript y Node.js',
            'fecha' => 'Octubre 2025',
            'horas' => 200,
            'descripcion' => 'Curso de desarrollo de aplicaciones web con Javascript y Node.js',
            'profesor' => 1
        ];

        $courseDB = [
            'nombre' => 'Desarrollo de aplicaciones con Javascript y Node.js',
            'fecha' => 'Octubre 2025',
            'horas' => 200,
            'descripcion' => 'Curso de desarrollo de aplicaciones web con Javascript y Node.js',
            'teacher_id' => 1
        ];

        $response = $this->actingAs($this->create_user())->put('/cursos/1', $course);
        $response->assertRedirectToRoute('cursos.index');
        $this->assertDatabaseHas('courses', $courseDB);
    }

    /**
    * Test to enroll students to a specific course .
    */
    public function test_enroll_student()
    {
        $courseStudent = [
            'curso' => 1,
            'alumno' => 1
        ];

        $courseStudentDB = [
            'course_id' => 1,
            'student_id' => 1
        ];

        $response = $this->actingAs($this->create_user())->post('/cursoalumno', $courseStudent);
        $response->assertStatus(200); // Una vez hecho el post nos redirige al index
        $this->assertDatabaseHas('course_students', $courseStudentDB);
    }

    /**
    * Test to see students enrolled to a specific course .
    */
    public function test_get_enrolled_students()
    {
        $student = (object) [
            'idRegistro' => 1,
            'id' => 1,
            'nombre' => 'Jaimito',
            'apellidos' => 'Pérez',
            'email' => 'jperez@gmail.com',
            'dni' => '67838733A'
        ];
        
        $response = $this->actingAs($this->create_user())->get('/cursos/1');
        $response->assertStatus(200);
        $response->assertViewHas('students', function($students) use ($student) {
            return $students->contains('nombre', $student->nombre);
        });
    }

    /**
    * Test to unenroll students of a specific course.
    */
    public function test_unenroll_student()
    {
        $student = (object) [
            'idRegistro' => 1,
            'id' => 1,
            'nombre' => 'Jaimito',
            'apellidos' => 'Pérez',
            'email' => 'jperez@gmail.com',
            'dni' => '67838733A'
        ];

        $courseStudentDB = [
            'id' => 1,
            'course_id' => 1,
            'student_id' => 1
        ];
        
        $this->actingAs($this->create_user())->delete('/cursoalumno/1');
        $response = $this->actingAs($this->create_user())->get('/cursos/1');
        $response->assertStatus(200);
        $response->assertViewMissing($student);
        $this->assertDatabaseMissing('course_students', $courseStudentDB);
    }

    /**
    * Test to delete a specific course.
    */
    public function test_delete_course ()
    {
        $response = $this->actingAs($this->create_user())->delete('/cursos/1');
        $response->assertRedirectToRoute('cursos.index');
        $this->assertDatabaseEmpty('courses'); // Sólo hemos creado uno, la BD debe estar vacía
    }
}
