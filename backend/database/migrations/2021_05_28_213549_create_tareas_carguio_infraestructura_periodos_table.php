<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasCarguioInfraestructuraPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas_carguio_infraestructura_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_carguio');
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->integer('turno')->nullable();
            $table->boolean('termino')->default(false);
            $table->integer('orden')->nullable();
            $table->string('nombre_tarea')->nullable();
            $table->decimal('porcentaje_avance',15,2)->default(0.00);
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
        Schema::dropIfExists('tareas_carguio_infraestructura_periodos');
    }
}