<?php

namespace App\Http\Controllers\Cronograma;

use Illuminate\Http\Request;
use App\Models\Cronograma\ValorInfraestructuraPeriodo;
use App\Models\Operacion\PerforacionInfraestructuraPeriodo;
use App\Models\Operacion\TronaduraInfraestructuraPeriodo;
use App\Models\Operacion\CarguioInfraestructuraPeriodo;
use App\Models\DatosMina\DatosMina;
use App\Repositories\Cronograma\CronogramaInfraestructuraPeriodoRepository;
use App\Repositories\Cronograma\ValorInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\PerforacionInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\TronaduraInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\CarguioInfraestructuraPeriodoRepository;
use App\Http\Requests\Cronograma\InfraestructuraPeriodo\ShowInfraestructuraPeriodo;
use App\Http\Requests\Cronograma\InfraestructuraPeriodo\CreateInfraestructuraPeriodo;
use App\Http\Requests\Cronograma\InfraestructuraPeriodo\EditInfraestructuraPeriodo;
use App\Http\Requests\Cronograma\InfraestructuraPeriodo\DeleteInfraestructuraPeriodo;
use App\Http\Requests\Cronograma\ValorInfraestructuraPeriodo\ShowValorInfraestructuraPeriodo;
use App\Http\Requests\Cronograma\ValorInfraestructuraPeriodo\CreateValorInfraestructuraPeriodo;
use App\Http\Requests\Cronograma\ValorInfraestructuraPeriodo\EditValorInfraestructuraPeriodo;
use App\Http\Requests\Cronograma\ValorInfraestructuraPeriodo\DeleteValorInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\ShowPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\CreatePerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\EditPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\DeletePerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\ShowTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\CreateTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\EditTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\DeleteTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\ShowCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\CreateCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\EditCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\DeleteCarguioInfraestructuraPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;

class CronogramaInfraestructuraPeriodoController extends Controller
{
    //Se incluye el repositorio de CronogramaInfraestructuraPeriodo
    /** @var CronogramaInfraestructuraPeriodoRepository */
    private $repository;

    public function __construct(CronogramaInfraestructuraPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowCronogramaInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowInfraestructuraPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data->load('valores');
    }

    /**
     * create InfraestructuraPeriodo
     * @return InfraestructuraPeriodo
     */
    public function store(CreateInfraestructuraPeriodo $request){
        $valor_repository = new ValorInfraestructuraPeriodoRepository(new ValorInfraestructuraPeriodo());
        $perforacion_repository = new PerforacionInfraestructuraPeriodoRepository(new PerforacionInfraestructuraPeriodo());
        $tronadura_repository = new TronaduraInfraestructuraPeriodoRepository(new TronaduraInfraestructuraPeriodo());
        $carguio_repository = new CarguioInfraestructuraPeriodoRepository(new CarguioInfraestructuraPeriodo());
        $data = $request->validated();
        $model = $this->repository->create(Arr::only($data, $this->repository->attributes()));
        foreach (Arr::get($data, 'valores', []) as $key => $valor) {
            $dataValor = array_merge($valor, ['id_infraestructura' => $model->id]);
            $valores = $valor_repository->create(Arr::only($dataValor, $valor_repository->attributes()));
            $dataPerforacion = [
                'id_infraestructura' => $model->id,
                'periodo' => $valor['periodo'],
                'ano' => $valor['ano'],
            ];
            $dataTronadura = [
                'id_infraestructura' => $model->id,
                'periodo' => $valor['periodo'],
                'ano' => $valor['ano'],
            ];
            $dataCarguio = [
                'id_infraestructura' => $model->id,
                'periodo' => $valor['periodo'],
                'ano' => $valor['ano'],
            ];
            $perforaciones = $perforacion_repository->create(Arr::only($dataPerforacion, $perforacion_repository->attributes()));
            $tronaduras = $tronadura_repository->create(Arr::only($dataTronadura, $tronadura_repository->attributes()));
            $carguio = $carguio_repository->create(Arr::only($dataCarguio, $carguio_repository->attributes()));
        }
        return $model->load(['valores','perforaciones','tronaduras','carguios']);
    }

    /**
     * delete InfraestructuraPeriodo
     * @return InfraestructuraPeriodo
     */
    public function destroy(DeleteInfraestructuraPeriodo $request, $slug){
        $detalles = $this->repository->find($slug);
        if ($detalles->delete() && $detalles->valores()->delete() && $detalles->perforaciones()->delete() && $detalles->tronaduras()->delete() && $detalles->carguios()->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'InfraestructuraPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el InfraestructuraPeriodo'
        ]);
    }

    /**
     * edit InfraestructuraPeriodo
     * @return InfraestructuraPeriodo
     */
    public function update(EditInfraestructuraPeriodo $request, $slug){
        $valor_repository = new ValorInfraestructuraPeriodoRepository(new ValorInfraestructuraPeriodo());
        $perforacion_repository = new PerforacionInfraestructuraPeriodoRepository(new PerforacionInfraestructuraPeriodo());
        $tronadura_repository = new TronaduraInfraestructuraPeriodoRepository(new TronaduraInfraestructuraPeriodo());
        $carguio_repository = new CarguioInfraestructuraPeriodoRepository(new CarguioInfraestructuraPeriodo());
        $data = $request->validated();
        $infraestructura = $this->repository->find($slug);
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
            $infraestructura->valores()->delete();
            $infraestructura->perforaciones()->delete();
            $infraestructura->tronaduras()->delete();
            $infraestructura->carguios()->delete();
            foreach (Arr::get($data, 'valores', []) as $key => $detalle) {
                $dataDetalle = array_merge($detalle, ['id_infraestructura' => $infraestructura->id]);
                $detalles = $valor_repository->create(Arr::only($dataDetalle, $valor_repository->attributes()));
                $dataPerforacion = [
                    'id_infraestructura' => $infraestructura->id,
                    'periodo' => $detalle['periodo'],
                    'ano' => $detalle['ano'],
                ];
                $dataTronadura = [
                    'id_infraestructura' => $infraestructura->id,
                    'periodo' => $detalle['periodo'],
                    'ano' => $detalle['ano'],
                ];
                $dataCarguio = [
                    'id_infraestructura' => $infraestructura->id,
                    'periodo' => $detalle['periodo'],
                    'ano' => $detalle['ano'],
                ];
                $perforaciones = $perforacion_repository->create(Arr::only($dataPerforacion, $perforacion_repository->attributes()));
                $tronaduras = $tronadura_repository->create(Arr::only($dataTronadura, $tronadura_repository->attributes()));
                $carguio = $carguio_repository->create(Arr::only($dataCarguio, $carguio_repository->attributes()));
            }
            $infraestructura->refresh();
            return $infraestructura->load(['valores','perforaciones','tronaduras','carguios']);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * Search InfraestructuraPeriodo with datos mina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscar_cronograma(ShowInfraestructuraPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        return $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load(['valores','perforaciones','tronaduras','carguios']);
    }

    /**
     * Search InfraestructuraPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function sumar_cronograma_anual(ShowInfraestructuraPeriodo $request){
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
     * Search InfraestructuraPeriodo and add all periods
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function sumar_cronograma_periodo(ShowInfraestructuraPeriodo $request){
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
     * Search InfraestructuraPeriodo and calculate cronograma
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_cronograma_periodo(ShowInfraestructuraPeriodo $request){
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
     * Search InfraestructuraPeriodo and calculate plan mina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_plan_mina_periodo(ShowInfraestructuraPeriodo $request){
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
     * Search InfraestructuraPeriodo and calculate perforaciones
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_perforaciones_periodo(ShowInfraestructuraPeriodo $request){
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
     * Search InfraestructuraPeriodo and calculate tronadura
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_tronadura_periodo(ShowInfraestructuraPeriodo $request){
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
     * Search InfraestructuraPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_cronograma_anual(ShowInfraestructuraPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_values = collect([]);
        $data_plan = [];
        $total_desgloce = 0;
        $anos_infra = [];
        $mas = 0;
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
                    if($ano > $mas){
                        array_push($anos_infra, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        $mas = max($anos_infra);
                    }
                }
            }
            array_push($data_plan,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_periodo' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'anos_infraestructura' => $anos_infra,
            'preparacion' => [],
            'anos_preparaciones' => [],
            'produccion' => [],
            'anos_produccion' => []
        ];
    }

    /**
     * Search InfraestructuraPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_plan_mina_anual(ShowInfraestructuraPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_values = collect([]);
        $data_plan = collect([]);
        $total_desgloce = 0;
        $anos_infra = [];
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
                    array_push($anos_infra, [
                        'key' => $ano,
                        'label' => 'Año '.$ano,
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
                'anos_infraestructura' => $anos_infra
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }

        return $data_plan;
    }

    /**
     * Search InfraestructuraPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_perforaciones_anual(ShowInfraestructuraPeriodo $request){
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
     * Search InfraestructuraPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_tronadura_anual(ShowInfraestructuraPeriodo $request){
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

    /**
     * Search InfraestructuraPeriodo and add all values year
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    /*
    public function anos_infraestructura (ShowInfraestructuraPeriodo $request) {
        $datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

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
                    
            }
    }
    */
}
