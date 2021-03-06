<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosMinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_minas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->integer('periodo_por_año')->nullable();
            $table->integer('ano')->nullable();
            $table->integer('periodo')->nullable();
            $table->integer('meses_por_periodo')->nullable();
            $table->integer('dias_por_mes')->nullable();
            $table->integer('turnos_por_dia')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->float('avance_tronadura',15,2)->default(0.00);
            $table->float('toneladas_incorporadas_tronadura',15,2)->default(0.00);
            $table->float('ritmo_extraccion',15,2)->default(0.00);
            $table->float('mineral_recuperado_modulo',15,2)->default(0.00);
            $table->float('mineral_recuperado_pilares',15,2)->default(0.00);
            $table->float('densidad_esteril',15,2)->default(0.00);
            $table->float('densidad_mineral',15,2)->default(0.00);
            $table->float('densidad_dilusion',15,2)->default(0.00);
            $table->float('ley_esteril',15,2)->default(0.00);
            $table->float('ley_mineral',15,2)->default(0.00);
            $table->float('ley_diluida',15,2)->default(0.00);
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
        Schema::dropIfExists('datos_minas');
    }
}
