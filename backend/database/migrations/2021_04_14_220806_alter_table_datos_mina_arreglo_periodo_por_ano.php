<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDatosMinaArregloPeriodoPorAno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_minas', function (Blueprint $table) {
            $table->dropColumn('periodo_por_año');
        });
        Schema::table('datos_minas', function (Blueprint $table) {
            $table->integer('periodo_por_ano')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datos_minas', function (Blueprint $table) {
            $table->dropColumn('periodo_por_ano');
        });
        Schema::table('datos_minas', function (Blueprint $table) {
            $table->integer('periodo_por_año')->nullable();
        });
    }
}
