<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValorProduccionPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_produccion_periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produccion');
            $table->integer('periodo')->nullable();
            $table->integer('ano')->nullable();
            $table->decimal('valor_desgloce',15,2)->default(0.00);
            $table->decimal('valor_desgloce_perforacion',15,2)->default(0.00);
            $table->decimal('valor_desgloce_carguio',15,2)->default(0.00);
            $table->decimal('valor_desgloce_tronadura',15,2)->default(0.00);
            $table->decimal('valor_desgloce_transporte',15,2)->default(0.00);
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
        Schema::dropIfExists('valor_produccion_periodos');
    }
}
