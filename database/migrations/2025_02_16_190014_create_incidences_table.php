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
            $table->id();
            $table->string('titulo');
            $table->text('sumario');
            $table->date('fecha'); // Sólo día, mes y año
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('incidenceable_id');
            $table->unsignedBigInteger('incidenceable_type');
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
