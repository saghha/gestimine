<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEventoPeriodoQuitIdOperacionInfraestructura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evento_periodos', function (Blueprint $table) {
            $table->dropColumn('id_operacion_infraestructura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evento_periodos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_operacion_infraestructura');
        });
    }
}
