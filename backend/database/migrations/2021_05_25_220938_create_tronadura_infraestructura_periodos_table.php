<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTronaduraInfraestructuraPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tronadura_infraestructura_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_infraestructura');
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->boolean('termino')->default(false);
            $table->decimal('registro_desgloce',15,2)->default(0.00);
            $table->decimal('valor_tronadura',15,2)->default(0.00);
            $table->decimal('total_tronadura',15,2)->default(0.00);
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
        Schema::dropIfExists('tronadura_infraestructura_periodos');
    }
}
