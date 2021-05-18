<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEventoPeriodoAddOperacionInfraestructura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evento_periodos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_operacion_infraestructura');
            $table->string('operacion_infraestructura')->nullable();
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
            $table->dropColumn('id_operacion_infraestructura');
            $table->dropColumn('operacion_infraestructura');
        });
    }
}
