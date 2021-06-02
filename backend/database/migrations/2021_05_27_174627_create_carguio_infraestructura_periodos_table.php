<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarguioInfraestructuraPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carguio_infraestructura_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_infraestructura');
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->boolean('termino')->default(false);
            $table->decimal('registro_desgloce_carguio',15,2)->default(0.00);
            $table->decimal('valor_carguio',15,2)->default(0.00);
            $table->decimal('total_carguio',15,2)->default(0.00);
            $table->decimal('registro_desgloce_total',15,2)->default(0.00);
            $table->decimal('valor_total',15,2)->default(0.00);
            $table->decimal('total_total',15,2)->default(0.00);
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
        Schema::dropIfExists('carguio_infraestructura_periodos');
    }
}
