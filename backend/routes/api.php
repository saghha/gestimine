<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::get('profile', 'AuthController@me');
});
Route::group(['prefix' => 'datos-mina', 'namespace' => 'DatosMina'], function(){
    Route::post('duplicar', 'DatosMinaController@duplicar');
    Route::get('ultimo', 'DatosMinaController@ultimo');
    Route::get('buscar', 'DatosMinaController@buscar');
    Route::apiResource('informacion', 'DatosMinaController');
});
Route::group(['prefix' => 'cronograma', 'namespace' => 'Cronograma'], function(){
    Route::group(['prefix' => 'infraestructura'], function(){
        Route::get('buscar-cronograma', 'CronogramaInfraestructuraPeriodoController@buscar_cronograma');
        Route::get('sumar-cronograma-ano', 'CronogramaInfraestructuraPeriodoController@sumar_cronograma_anual');
        Route::get('sumar-cronograma-periodo', 'CronogramaInfraestructuraPeriodoController@sumar_cronograma_periodo');
        Route::get('mostrar-cronograma-periodo', 'CronogramaInfraestructuraPeriodoController@mostrar_cronograma_periodo');
        Route::get('mostrar-plan-mina-periodo', 'CronogramaInfraestructuraPeriodoController@mostrar_plan_mina_periodo');
        Route::get('mostrar-perforaciones-periodo', 'CronogramaInfraestructuraPeriodoController@mostrar_perforaciones_periodo');
        Route::get('mostrar-tronaduras-periodo', 'CronogramaInfraestructuraPeriodoController@mostrar_tronadura_periodo');
        Route::get('mostrar-cronograma-anual', 'CronogramaInfraestructuraPeriodoController@mostrar_cronograma_anual');
        Route::get('mostrar-plan-mina-anual', 'CronogramaInfraestructuraPeriodoController@mostrar_plan_mina_anual');
        Route::get('mostrar-perforaciones-anual', 'CronogramaInfraestructuraPeriodoController@mostrar_perforaciones_anual');
        Route::get('mostrar-tronaduras-anual', 'CronogramaInfraestructuraPeriodoController@mostrar_tronadura_anual');
    });
    Route::apiResource('infraestructura', 'CronogramaInfraestructuraPeriodoController');
    Route::group(['prefix' => 'preparacion'], function(){
        Route::get('buscar-cronograma', 'CronogramaPreparacionPeriodoController@buscar_cronograma');
        Route::get('sumar-cronograma-ano', 'CronogramaPreparacionPeriodoController@sumar_cronograma_anual');
        Route::get('sumar-cronograma-periodo', 'CronogramaPreparacionPeriodoController@sumar_cronograma_periodo');
        Route::get('mostrar-cronograma-periodo', 'CronogramaPreparacionPeriodoController@mostrar_cronograma_periodo');
        Route::get('mostrar-plan-mina-periodo', 'CronogramaPreparacionPeriodoController@mostrar_plan_mina_periodo');
        Route::get('mostrar-perforaciones-periodo', 'CronogramaPreparacionPeriodoController@mostrar_perforaciones_periodo');
        Route::get('mostrar-tronaduras-periodo', 'CronogramaPreparacionPeriodoController@mostrar_tronadura_periodo');
        Route::get('mostrar-cronograma-anual', 'CronogramaPreparacionPeriodoController@mostrar_cronograma_anual');
        Route::get('mostrar-plan-mina-anual', 'CronogramaPreparacionPeriodoController@mostrar_plan_mina_anual');
        Route::get('mostrar-perforaciones-anual', 'CronogramaPreparacionPeriodoController@mostrar_perforaciones_anual');
        Route::get('mostrar-tronaduras-anual', 'CronogramaPreparacionPeriodoController@mostrar_tronadura_anual');
    });
    Route::apiResource('preparacion', 'CronogramaPreparacionPeriodoController');
    Route::group(['prefix' => 'produccion'], function(){
        Route::get('buscar-cronograma', 'CronogramaProduccionPeriodoController@buscar_cronograma');
        Route::get('sumar-cronograma-ano', 'CronogramaProduccionPeriodoController@sumar_cronograma_anual');
        Route::get('sumar-cronograma-periodo', 'CronogramaProduccionPeriodoController@sumar_cronograma_periodo');
        Route::get('mostrar-cronograma-periodo', 'CronogramaProduccionPeriodoController@mostrar_cronograma_periodo');
        Route::get('mostrar-cronograma-anual', 'CronogramaProduccionPeriodoController@mostrar_cronograma_anual');
    });
    Route::apiResource('produccion', 'CronogramaProduccionPeriodoController');
});
Route::group(['prefix' => 'registro-datos', 'namespace' => 'RegistroDatos'], function(){
    Route::group(['prefix' => 'evento'], function(){
        
    });
    Route::apiResource('evento', 'EventoPeriodoController');
});
Route::group(['prefix' => 'operacion', 'namespace' => 'Operacion'], function(){
    Route::group(['prefix' => 'perforacion-infraestructura'], function(){
        
    });
    Route::apiResource('perforacion-infraestructura', 'PerforacionInfraestructuraPeriodoController');
});
