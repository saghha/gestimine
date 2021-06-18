<?php

namespace App\Http\Controllers\Cronograma;

use Illuminate\Http\Request;
use App\Models\Cronograma\ValorPreparacionPeriodo;
use App\Models\DatosMina\DatosMina;
use App\Repositories\Cronograma\CronogramaPreparacionPeriodoRepository;
use App\Repositories\Cronograma\ValorPreparacionPeriodoRepository;
use App\Http\Requests\Cronograma\PreparacionPeriodo\ShowPreparacionPeriodo;
use App\Http\Requests\Cronograma\PreparacionPeriodo\CreatePreparacionPeriodo;
use App\Http\Requests\Cronograma\PreparacionPeriodo\EditPreparacionPeriodo;
use App\Http\Requests\Cronograma\PreparacionPeriodo\DeletePreparacionPeriodo;
use App\Http\Requests\Cronograma\ValorPreparacionPeriodo\ShowValorPreparacionPeriodo;
use App\Http\Requests\Cronograma\ValorPreparacionPeriodo\CreateValorPreparacionPeriodo;
use App\Http\Requests\Cronograma\ValorPreparacionPeriodo\EditValorPreparacionPeriodo;
use App\Http\Requests\Cronograma\ValorPreparacionPeriodo\DeleteValorPreparacionPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;

class CronogramaPreparacionPeriodoController extends Controller
{
    //Se incluye el repositorio de CronogramaPreparacionPeriodo
    /** @var CronogramaPreparacionPeriodoRepository */
    private $repository;

    public function __construct(CronogramaPreparacionPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowCronogramanPreparacionPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowPreparacionPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data->load('valores');
    }

    /**
     * get a ShowPreparacionInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function show(ShowPreparacionPeriodo $request, $slug){
        $data = $this->repository->find($slug);
        return $data->load('valores');
    }

    /**
     * create PreparacionPeriodo
     * @return PreparacionPeriodo
     */
    public function store(CreatePreparacionPeriodo $request){
        $valor_repository = new ValorPreparacionPeriodoRepository(new ValorPreparacionPeriodo());
        $data = $request->validated();
        $model = $this->repository->create(Arr::only($data, $this->repository->attributes()));
        foreach (Arr::get($data, 'valores', []) as $key => $valor) {
            $dataValor = array_merge($valor, ['id_preparacion' => $model->id]);
            $valores = $valor_repository->create(Arr::only($dataValor, $valor_repository->attributes()));
        }
        return $model->load('valores');
    }

    /**
     * edit PreparacionPeriodo
     * @return PreparacionPeriodo
     */
    public function update(EditPreparacionPeriodo $request, $slug){
        $valor_repository = new ValorPreparacionPeriodoRepository(new ValorPreparacionPeriodo());
        $data = $request->validated();
        $preparacion = $this->repository->find($slug);
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
            $preparacion->valores()->delete();
            foreach (Arr::get($data, 'valores', []) as $key => $detalle) {
                $dataDetalle = array_merge($detalle, ['id_preparacion' => $preparacion->id]);
                $detalles = $valor_repository->create(Arr::only($dataDetalle, $valor_repository->attributes()));
            }
            $preparacion->refresh();
            return $preparacion->load('valores');
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * delete PreparacionPeriodo
     * @return PreparacionPeriodo
     */
    public function destroy(DeletePreparacionPeriodo $request, $slug){
        $detalles = $this->repository->find($slug);
        if ($detalles->delete() && $detalles->valores()->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'PreparacionPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el PreparacionPeriodo'
        ]);
    }

    /**
     * Search PreparacionPeriodo with datos mina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscar_cronograma(ShowPreparacionPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        return $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');
    }

    /**
     * Search PreparacionPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function sumar_cronograma_anual(ShowPreparacionPeriodo $request){
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
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                }
            }
        }

        return $data_values;
    }

    /**
     * Search PreparacionPeriodo and add all periods
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function sumar_cronograma_periodo(ShowPreparacionPeriodo $request){
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
                        $valor_desgloce_t = $data_values[$value_2->periodo]['valor_desgloce_anual'] + $value_2->valor_desgloce;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_anual' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_anual' => $valor_desgloce_t,
                        ]);
                    }
                }
            }
        }

        return $data_values;
    }

    /**
     * Search PreparacionPeriodo and calculate cronograma
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_cronograma_periodo(ShowPreparacionPeriodo $request){
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
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    }
                    $total_desgloce += $valor_desgloce_t;
                }
            }
            $data_plan->put($value->id,[
                'ano' => $ano,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
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
     * Search PreparacionPeriodo and calculate plan mina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_plan_mina_periodo(ShowPreparacionPeriodo $request){
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
                        $valor_desgloce_t = $data_values[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t*($value->area*$value->densidad_esteril),
                        ]);
                    }
                    $total_desgloce += $valor_desgloce_t*($value->area*$value->densidad_esteril);
                }
            }
            $data_plan->put($value->id,[
                'ano' => $ano,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
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
     * Search PreparacionPeriodo and calculate perforaciones
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_perforaciones_periodo(ShowPreparacionPeriodo $request){
        $datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $datos_mina->avance_tronadura;
        $profundidad_tiro = $datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_plan = collect([]);
        $total_desgloce = 0;
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values->has($value_2->periodo)) {
                        $periodo = $data_values[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values[$value_2->periodo]['valor_desgloce_periodo'] + ($value_2->valor_desgloce/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => ($valor_desgloce_t/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro,
                        ]);
                    }
                    $total_desgloce += ($valor_desgloce_t/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                }
            }
            $data_plan->put($value->id,[
                'ano' => $ano,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_periodo' => $total_desgloce,
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

    /**
     * Search PreparacionPeriodo and calculate tronadura
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_tronadura_periodo(ShowPreparacionPeriodo $request){
        $datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $datos_mina->avance_tronadura;
        $profundidad_tiro = $datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_plan = collect([]);
        $total_desgloce = 0;
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values->has($value_2->periodo)) {
                        $periodo = $data_values[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values[$value_2->periodo]['valor_desgloce_periodo'] + ($value_2->valor_desgloce/$avance_tronadura);
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => ($valor_desgloce_t/$avance_tronadura),
                        ]);
                    }
                    $total_desgloce += ($valor_desgloce_t/$avance_tronadura);
                }
            }
            $data_plan->put($value->id,[
                'ano' => $ano,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_periodo' => $total_desgloce,
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

    /**
     * Search PreparacionPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_cronograma_anual(ShowPreparacionPeriodo $request){
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
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                }
            }
            $data_plan->put($value->id,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_periodo' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

    /**
     * Search PreparacionPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_plan_mina_anual(ShowPreparacionPeriodo $request){
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
                    $valor_desgloce_t = $data_values[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t*($value->area*$value->densidad_esteril),
                    ]);
                }
            }
            $data_plan->put($value->id,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

    /**
     * Search PreparacionPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_perforaciones_anual(ShowPreparacionPeriodo $request){
        $datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $datos_mina->avance_tronadura;
        $profundidad_tiro = $datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_plan = collect([]);
        $total_desgloce = 0;
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($data_values->has($value_2->ano)) {
                    $ano = $data_values[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values[$value_2->ano]['valor_desgloce_anual'] + ($valor_desgloce_t/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => ($valor_desgloce_t/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro,
                    ]);
                }
            }
            $data_plan->put($value->id,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

    /**
     * Search PreparacionPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_tronadura_anual(ShowPreparacionPeriodo $request){
        $datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $datos_mina->avance_tronadura;
        $profundidad_tiro = $datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_plan = collect([]);
        $total_desgloce = 0;
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($data_values->has($value_2->ano)) {
                    $ano = $data_values[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values[$value_2->ano]['valor_desgloce_anual'] + ($valor_desgloce_t/$avance_tronadura);
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => ($valor_desgloce_t/$avance_tronadura),
                    ]);
                }
            }
            $data_plan->put($value->id,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values,
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }
}
