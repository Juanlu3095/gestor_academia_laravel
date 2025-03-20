<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titulo');
            $table->text('sumario');
            $table->date('fecha'); // Sólo día, mes y año
            $table->uuid('document_id')->nullable(); // El tipo de dato debe coincidir con el de tabla referenciada
            $table->unsignedBigInteger('incidenceable_id'); // Hace referencia a la id de alumnos o profesores
            $table->enum('incidenceable_type', ['Alumno', 'Profesor']); // Sólo puede tomar dos valores: 'Alumno' o 'Profesor'
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidences');
    }
};
