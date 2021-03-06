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
Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'datos-mina', 'namespace' => 'DatosMina'], function(){
    Route::post('duplicar', 'DatosMinaController@duplicar');
    Route::get('ultimo', 'DatosMinaController@ultimo');
    Route::get('buscar', 'DatosMinaController@buscar');
    Route::get('periodo', 'DatosMinaController@periodo');
    Route::apiResource('informacion', 'DatosMinaController');
});
Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'cronograma', 'namespace' => 'Cronograma'], function(){
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
        Route::get('items', 'EventoPeriodoController@items');
    });
    Route::apiResource('evento', 'EventoPeriodoController');
});
Route::group(['prefix' => 'operacion', 'namespace' => 'Operacion'], function(){
    Route::group(['prefix' => 'perforacion-infraestructura'], function(){
        Route::get('buscar', 'PerforacionInfraestructuraPeriodoController@buscar');
    });
    Route::apiResource('perforacion-infraestructura', 'PerforacionInfraestructuraPeriodoController');
    Route::group(['prefix' => 'tareas-perforacion-inf'], function(){
        Route::post('generica', 'TareasPerforacionInfraestructuraPeriodoController@generica');
        Route::get('tarea-activa/{slug}', 'TareasPerforacionInfraestructuraPeriodoController@tarea_activa');
        Route::post('editar-tarea-activa', 'TareasPerforacionInfraestructuraPeriodoController@editar_tarea_activa');
        Route::post('reiniciar-tarea-activa', 'TareasPerforacionInfraestructuraPeriodoController@reiniciar_tarea_activa');
    });
    Route::apiResource('tareas-perforacion-inf', 'TareasPerforacionInfraestructuraPeriodoController');
    Route::group(['prefix' => 'tronadura-infraestructura'], function(){
        Route::get('buscar', 'TronaduraInfraestructuraPeriodoController@buscar');
    });
    Route::apiResource('tronadura-infraestructura', 'TronaduraInfraestructuraPeriodoController');
    Route::group(['prefix' => 'tareas-tronadura-inf'], function(){
        Route::post('generica', 'TareasTronaduraInfraestructuraPeriodoController@generica');
        Route::get('tarea-activa/{slug}', 'TareasTronaduraInfraestructuraPeriodoController@tarea_activa');
        Route::post('editar-tarea-activa', 'TareasTronaduraInfraestructuraPeriodoController@editar_tarea_activa');
        Route::post('reiniciar-tarea-activa', 'TareasTronaduraInfraestructuraPeriodoController@reiniciar_tarea_activa');
    });
    Route::apiResource('tareas-tronadura-inf', 'TareasTronaduraInfraestructuraPeriodoController');
    Route::group(['prefix' => 'carguio-infraestructura'], function(){
        Route::get('buscar', 'CarguioInfraestructuraPeriodoController@buscar');
    });
    Route::apiResource('carguio-infraestructura', 'CarguioInfraestructuraPeriodoController');
    Route::group(['prefix' => 'tareas-carguio-inf'], function(){
        
    });
    Route::apiResource('tareas-carguio-inf', 'TareasCarguioInfraestructuraPeriodoController');
});
