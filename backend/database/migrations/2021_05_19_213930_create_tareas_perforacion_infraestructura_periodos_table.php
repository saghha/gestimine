<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasPerforacionInfraestructuraPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas_perforacion_infraestructura_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perforacion');
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->integer('turno')->nullable();
            $table->boolean('termino')->default(false);
            $table->integer('orden')->nullable();
            $table->string('nombre_tarea')->nullable();
            $table->float('porcentaje_avance',15,2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas_perforacion_infraestructura_periodos');
    }
}
