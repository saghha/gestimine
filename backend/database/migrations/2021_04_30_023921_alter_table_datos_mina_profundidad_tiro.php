<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDatosMinaProfundidadTiro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_minas', function (Blueprint $table) {
            $table->decimal('profundidad_tiro',15,2)->default(0.00);
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
            $table->dropColumn('profundidad_tiro');
        });
    }
}
