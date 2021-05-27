<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCronogramaInfraestructuraPeriodosAgregarNroTiros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cronograma_infraestructura_periodos', function (Blueprint $table) {
            $table->integer('nro_tiros')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cronograma_infraestructura_periodos', function (Blueprint $table) {
            $table->integer('nro_tiros')->nullable();
        });
    }
}
