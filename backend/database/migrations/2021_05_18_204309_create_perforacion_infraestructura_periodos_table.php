<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerforacionInfraestructuraPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perforacion_infraestructura_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_infraestructura');
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->boolean('termino')->default(false);
            $table->float('registro_desgloce',15,2)->default(0.00);
            $table->float('valor_perforacion',15,2)->default(0.00);
            $table->float('total_perforacion',15,2)->default(0.00);
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
        Schema::dropIfExists('perforacion_infraestructura_periodos');
    }
}
