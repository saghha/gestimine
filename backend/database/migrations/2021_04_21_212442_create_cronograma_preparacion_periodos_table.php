<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronogramaPreparacionPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronograma_preparacion_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_datos_mina');
            $table->integer('nro_modulo')->nullable();
            $table->string('nombre_infraestructura')->nullable();
            $table->string('seccion')->nullable();
            $table->float('area',15,2)->default(0.00);
            $table->float('longitud',15,2)->default(0.00);
            $table->integer('nro_tiros')->nullable();
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->float('total_desgloce',15,2)->default(0.00);
            $table->float('densidad_esteril',15,2)->default(0.00);
            $table->float('ley_diluida',15,2)->default(0.00);
            $table->float('ley_mineral',15,2)->default(0.00);
            $table->float('ley_esteril',15,2)->default(0.00);
            $table->float('densidad_dilucion',15,2)->default(0.00);
            $table->float('densidad_mineral',15,2)->default(0.00);
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
        Schema::dropIfExists('cronograma_preparacion_periodos');
    }
}
