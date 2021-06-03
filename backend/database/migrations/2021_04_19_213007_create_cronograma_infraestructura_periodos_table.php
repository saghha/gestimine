<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronogramaInfraestructuraPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronograma_infraestructura_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_datos_mina');
            $table->string('nombre_infraestructura')->nullable();
            $table->string('seccion')->nullable();
            $table->decimal('area',15,2)->default(0.00);
            $table->decimal('longitud',15,2)->default(0.00);
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->decimal('total_desgloce',15,2)->default(0.00);
            $table->decimal('densidad_esteril',15,2)->default(0.00);
            $table->decimal('ley_diluida',15,2)->default(0.00);
            $table->decimal('ley_mineral',15,2)->default(0.00);
            $table->decimal('ley_esteril',15,2)->default(0.00);
            $table->decimal('densidad_dilucion',15,2)->default(0.00);
            $table->decimal('densidad_mineral',15,2)->default(0.00);
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
        Schema::dropIfExists('cronograma_infraestructura_periodos');
    }
}