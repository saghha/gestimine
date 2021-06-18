<?php

namespace App\Http\Controllers\Cronograma;

use Illuminate\Http\Request;
use App\Models\Cronograma\ValorProduccionPeriodo;
use App\Models\DatosMina\DatosMina;
use App\Repositories\Cronograma\CronogramaProduccionPeriodoRepository;
use App\Repositories\Cronograma\ValorProduccionPeriodoRepository;
use App\Http\Requests\Cronograma\ProduccionPeriodo\ShowProduccionPeriodo;
use App\Http\Requests\Cronograma\ProduccionPeriodo\CreateProduccionPeriodo;
use App\Http\Requests\Cronograma\ProduccionPeriodo\EditProduccionPeriodo;
use App\Http\Requests\Cronograma\ProduccionPeriodo\DeleteProduccionPeriodo;
use App\Http\Requests\Cronograma\ValorProduccionPeriodo\ShowValorProduccionPeriodo;
use App\Http\Requests\Cronograma\ValorProduccionPeriodo\CreateValorProduccionPeriodo;
use App\Http\Requests\Cronograma\ValorProduccionPeriodo\EditValorProduccionPeriodo;
use App\Http\Requests\Cronograma\ValorProduccionPeriodo\DeleteValorProduccionPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;

class CronogramaProduccionPeriodoController extends Controller
{
    //Se incluye el repositorio de CronogramaProduccionPeriodo
    /** @var CronogramaProduccionPeriodoRepository */
    private $repository;

    public function __construct(CronogramaProduccionPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowCronogramanProduccionPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowProduccionPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data->load('valores');
    }

    /**
     * get a ShowProduccionInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function show(ShowProduccionPeriodo $request, $slug){
        $data = $this->repository->find($slug);
        return $data->load('valores');
    }

    /**
     * create ProduccionPeriodo
     * @return ProduccionPeriodo
     */
    public function store(CreateProduccionPeriodo $request){
        $valor_repository = new ValorProduccionPeriodoRepository(new ValorProduccionPeriodo());
        $data = $request->validated();
        $model = $this->repository->create(Arr::only($data, $this->repository->attributes()));
        foreach (Arr::get($data, 'valores', []) as $key => $valor) {
            $dataValor = array_merge($valor, ['id_produccion' => $model->id]);
            $valores = $valor_repository->create(Arr::only($dataValor, $valor_repository->attributes()));
        }
        return $model->load('valores');
    }

    /**
     * edit ProduccionPeriodo
     * @return ProduccionPeriodo
     */
    public function update(EditProduccionPeriodo $request, $slug){
        $valor_repository = new ValorProduccionPeriodoRepository(new ValorProduccionPeriodo());
        $data = $request->validated();
        $produccion = $this->repository->find($slug);
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
            $produccion->valores()->delete();
            foreach (Arr::get($data, 'valores', []) as $key => $detalle) {
                $dataDetalle = array_merge($detalle, ['id_produccion' => $produccion->id]);
                $detalles = $valor_repository->create(Arr::only($dataDetalle, $valor_repository->attributes()));
            }
            $produccion->refresh();
            return $produccion->load('valores');
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * delete ProduccionPeriodo
     * @return ProduccionPeriodo
     */
    public function destroy(DeleteProduccionPeriodo $request, $slug){
        $detalles = $this->repository->find($slug);
        if ($detalles->delete() && $detalles->valores()->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'ProduccionPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el ProduccionPeriodo'
        ]);
    }

    /**
     * Search ProduccionPeriodo with datos mina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscar_cronograma(ShowProduccionPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        return $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');
    }

    /**
     * Search ProduccionPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function sumar_cronograma_anual(ShowProduccionPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_values = collect([]);
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($data_values->has($value_2->ano)) {
                    $ano = $data_values[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce;
                    $valor_desgloce_perforacion_t = $data_values[$value_2->ano]['valor_desgloce_perforacion_anual'] + $value_2->valor_desgloce_perforacion;
                    $valor_desgloce_carguio_t = $data_values[$value_2->ano]['valor_desgloce_carguio_anual'] + $value_2->valor_desgloce_carguio;
                    $valor_desgloce_tronadura_t = $data_values[$value_2->ano]['valor_desgloce_tronadura_anual'] + $value_2->valor_desgloce_tronadura;
                    $valor_desgloce_transporte_t = $data_values[$value_2->ano]['valor_desgloce_transporte_anual'] + $value_2->valor_desgloce_transporte;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                        'valor_desgloce_perforacion_anual' => $valor_desgloce_perforacion_t,
                        'valor_desgloce_carguio_anual' => $valor_desgloce_carguio_t,
                        'valor_desgloce_tronadura_anual' => $valor_desgloce_tronadura_t,
                        'valor_desgloce_transporte_anual' => $valor_desgloce_transporte_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $valor_desgloce_perforacion_t = $value_2->valor_desgloce_perforacion;
                    $valor_desgloce_carguio_t = $value_2->valor_desgloce_carguio;
                    $valor_desgloce_tronadura_t = $value_2->valor_desgloce_tronadura;
                    $valor_desgloce_transporte_t = $value_2->valor_desgloce_transporte;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                        'valor_desgloce_perforacion_anual' => $valor_desgloce_perforacion_t,
                        'valor_desgloce_carguio_anual' => $valor_desgloce_carguio_t,
                        'valor_desgloce_tronadura_anual' => $valor_desgloce_tronadura_t,
                        'valor_desgloce_transporte_anual' => $valor_desgloce_transporte_t,
                    ]);
                }
            }
        }

        return $data_values;
    }

    /**
     * Search ProduccionPeriodo and add all periods
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function sumar_cronograma_periodo(ShowProduccionPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $ano = $request->ano;

        $data_values = collect([]);
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values->has($value_2->periodo)) {
                        $periodo = $data_values[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce;
                        $valor_desgloce_perforacion_t = $data_values[$value_2->ano]['valor_desgloce_perforacion_periodo'] + $value_2->valor_desgloce_perforacion;
                        $valor_desgloce_carguio_t = $data_values[$value_2->ano]['valor_desgloce_carguio_periodo'] + $value_2->valor_desgloce_carguio;
                        $valor_desgloce_tronadura_t = $data_values[$value_2->ano]['valor_desgloce_tronadura_periodo'] + $value_2->valor_desgloce_tronadura;
                        $valor_desgloce_transporte_t = $data_values[$value_2->ano]['valor_desgloce_transporte_periodo'] + $value_2->valor_desgloce_transporte;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                            'valor_desgloce_perforacion_periodo' => $valor_desgloce_perforacion_t,
                            'valor_desgloce_carguio_periodo' => $valor_desgloce_carguio_t,
                            'valor_desgloce_tronadura_periodo' => $valor_desgloce_tronadura_t,
                            'valor_desgloce_transporte_periodo' => $valor_desgloce_transporte_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $valor_desgloce_perforacion_t = $value_2->valor_desgloce_perforacion;
                        $valor_desgloce_carguio_t = $value_2->valor_desgloce_carguio;
                        $valor_desgloce_tronadura_t = $value_2->valor_desgloce_tronadura;
                        $valor_desgloce_transporte_t = $value_2->valor_desgloce_transporte;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                            'valor_desgloce_perforacion_periodo' => $valor_desgloce_perforacion_t,
                            'valor_desgloce_carguio_periodo' => $valor_desgloce_carguio_t,
                            'valor_desgloce_tronadura_periodo' => $valor_desgloce_tronadura_t,
                            'valor_desgloce_transporte_periodo' => $valor_desgloce_transporte_t,
                        ]);
                    }
                }
            }
        }

        return $data_values;
    }

    /**
     * Search ProduccionPeriodo and calculate cronograma
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_cronograma_periodo(ShowProduccionPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $ano = $request->ano;

        $data_values = collect([]);
        $data_plan = collect([]);
        $total_desgloce = 0;
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values->has($value_2->periodo)) {
                        $periodo = $data_values[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce;
                        $valor_desgloce_perforacion_t = $data_values[$value_2->ano]['valor_desgloce_perforacion_periodo'] + $value_2->valor_desgloce_perforacion;
                        $valor_desgloce_carguio_t = $data_values[$value_2->ano]['valor_desgloce_carguio_periodo'] + $value_2->valor_desgloce_carguio;
                        $valor_desgloce_tronadura_t = $data_values[$value_2->ano]['valor_desgloce_tronadura_periodo'] + $value_2->valor_desgloce_tronadura;
                        $valor_desgloce_transporte_t = $data_values[$value_2->ano]['valor_desgloce_transporte_periodo'] + $value_2->valor_desgloce_transporte;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                            'valor_desgloce_perforacion_periodo' => $valor_desgloce_perforacion_t,
                            'valor_desgloce_carguio_periodo' => $valor_desgloce_carguio_t,
                            'valor_desgloce_tronadura_periodo' => $valor_desgloce_tronadura_t,
                            'valor_desgloce_transporte_periodo' => $valor_desgloce_transporte_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $valor_desgloce_perforacion_t = $value_2->valor_desgloce_perforacion;
                        $valor_desgloce_carguio_t = $value_2->valor_desgloce_carguio;
                        $valor_desgloce_tronadura_t = $value_2->valor_desgloce_tronadura;
                        $valor_desgloce_transporte_t = $value_2->valor_desgloce_transporte;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                            'valor_desgloce_perforacion_periodo' => $valor_desgloce_perforacion_t,
                            'valor_desgloce_carguio_periodo' => $valor_desgloce_carguio_t,
                            'valor_desgloce_tronadura_periodo' => $valor_desgloce_tronadura_t,
                            'valor_desgloce_transporte_periodo' => $valor_desgloce_transporte_t,
                        ]);
                    }
                    $total_desgloce += $valor_desgloce_t;
                }
            }
            $data_plan->put($value->id,[
                'ano' => $ano,
                'nombre' => $value->nombre_produccion,
                'densidad_esteril' => $value->densidad_esteril,
                'total_desgloce_periodo' => $total_desgloce,
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

    /**
     * Search ProduccionPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_cronograma_anual(ShowProduccionPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_values = collect([]);
        $data_plan = collect([]);
        $total_desgloce = 0;
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($data_values->has($value_2->ano)) {
                    $ano = $data_values[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce;
                    $valor_desgloce_perforacion_t = $data_values[$value_2->ano]['valor_desgloce_perforacion_periodo'] + $value_2->valor_desgloce_perforacion;
                    $valor_desgloce_carguio_t = $data_values[$value_2->ano]['valor_desgloce_carguio_periodo'] + $value_2->valor_desgloce_carguio;
                    $valor_desgloce_tronadura_t = $data_values[$value_2->ano]['valor_desgloce_tronadura_periodo'] + $value_2->valor_desgloce_tronadura;
                    $valor_desgloce_transporte_t = $data_values[$value_2->ano]['valor_desgloce_transporte_periodo'] + $value_2->valor_desgloce_transporte;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                        'valor_desgloce_perforacion_periodo' => $valor_desgloce_perforacion_t,
                        'valor_desgloce_carguio_periodo' => $valor_desgloce_carguio_t,
                        'valor_desgloce_tronadura_periodo' => $valor_desgloce_tronadura_t,
                        'valor_desgloce_transporte_periodo' => $valor_desgloce_transporte_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $valor_desgloce_perforacion_t = $value_2->valor_desgloce_perforacion;
                    $valor_desgloce_carguio_t = $value_2->valor_desgloce_carguio;
                    $valor_desgloce_tronadura_t = $value_2->valor_desgloce_tronadura;
                    $valor_desgloce_transporte_t = $value_2->valor_desgloce_transporte;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                        'valor_desgloce_perforacion_periodo' => $valor_desgloce_perforacion_t,
                        'valor_desgloce_carguio_periodo' => $valor_desgloce_carguio_t,
                        'valor_desgloce_tronadura_periodo' => $valor_desgloce_tronadura_t,
                        'valor_desgloce_transporte_periodo' => $valor_desgloce_transporte_t,
                    ]);
                }
            }
            $data_plan->put($value->id,[
                'nombre' => $value->nombre_produccion,
                'densidad_esteril' => $value->densidad_esteril,
                'total_desgloce_periodo' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

}
