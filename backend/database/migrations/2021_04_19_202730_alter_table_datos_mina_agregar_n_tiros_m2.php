<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDatosMinaAgregarNTirosM2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_minas', function (Blueprint $table) {
            $table->decimal('tiros_por_m2',15,2)->default(0.00);
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
            $table->dropColumn('tiros_por_m2');
        });
    }
}
