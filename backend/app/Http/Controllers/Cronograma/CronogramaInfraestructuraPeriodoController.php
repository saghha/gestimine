<?php

namespace App\Http\Controllers\Cronograma;

use Illuminate\Http\Request;
use App\Models\Cronograma\CronogramaPreparacionPeriodo;
use App\Models\Cronograma\CronogramaProduccionPeriodo;
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
use Illuminate\Support\Facades\DB;
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
     * get a ShowCronogramaInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function show(ShowInfraestructuraPeriodo $request, $slug){
        $data = $this->repository->find($slug);
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
        DB::beginTransaction();
        try {
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
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();
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
        $save_perforacion =  $infraestructura->perforaciones()->get();
        $save_tronaduras =  $infraestructura->tronaduras()->get();
        $save_carguios =  $infraestructura->carguios()->get();
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
            DB::beginTransaction();
            try {
                $infraestructura->valores()->delete();
                $infraestructura->perforaciones()->delete();
                $infraestructura->tronaduras()->delete();
                $infraestructura->carguios()->delete();
                foreach (Arr::get($data, 'valores', []) as $key => $detalle) {
                    $dataDetalle = array_merge($detalle, ['id_infraestructura' => $infraestructura->id]);
                    $detalles = $valor_repository->create(Arr::only($dataDetalle, $valor_repository->attributes()));
                    $registro_perforacion = $save_perforacion->where('periodo', $detalle['periodo'])->where('ano', $detalle['ano'])->first();
                    $dataPerforacion = [
                        'id_infraestructura' => $infraestructura->id,
                        'periodo' => $detalle['periodo'],
                        'ano' => $detalle['ano'],
                        'registro_desgloce' => $registro_perforacion->registro_desgloce,
                        'valor_perforacion' => $registro_perforacion->valor_perforacion,
                        'total_perforacion' => $registro_perforacion->total_perforacion,
                    ];
                    $registro_tronadura = $save_tronaduras->where('periodo', $detalle['periodo'])->where('ano', $detalle['ano'])->first();
                    $dataTronadura = [
                        'id_infraestructura' => $infraestructura->id,
                        'periodo' => $detalle['periodo'],
                        'ano' => $detalle['ano'],
                        'registro_desgloce' => $registro_tronadura->registro_desgloce,
                        'valor_tronadura' => $registro_tronadura->valor_tronadura,
                        'total_tronadura' => $registro_tronadura->total_tronadura,
                    ];
                    $registro_carguio = $save_carguios->where('periodo', $detalle['periodo'])->where('ano', $detalle['ano'])->first();
                    $dataCarguio = [
                        'id_infraestructura' => $infraestructura->id,
                        'periodo' => $detalle['periodo'],
                        'ano' => $detalle['ano'],
                        'registro_desgloce_carguio' => $registro_carguio->registro_desgloce_carguio,
                        'valor_cargio' => $registro_carguio->valor_carguio,
                        'total_carguio' => $registro_carguio->total_carguio,
                        'registro_desgloce_total' => $registro_carguio->registro_desgloce_total,
                        'valor_total' => $registro_carguio->valor_total,
                        'total_total' => $registro_carguio->total_total,
                    ];
                    $perforaciones = $perforacion_repository->create(Arr::only($dataPerforacion, $perforacion_repository->attributes()));
                    $tronaduras = $tronadura_repository->create(Arr::only($dataTronadura, $tronadura_repository->attributes()));
                    $carguio = $carguio_repository->create(Arr::only($dataCarguio, $carguio_repository->attributes()));
                }
                $infraestructura->refresh();
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
            DB::commit();
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

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $ano = $request->ano;

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $periodo_infra = [];
        $periodo_prep = [];
        $periodo_prod = [];
        $mas = 0;
        $array_periodo = [];
        //calcula cronograma infraestructura
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
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_infra, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values->sum('valor_desgloce_periodo'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula cronograma preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prep->has($value_2->periodo)) {
                        $periodo = $data_values_prep[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values_prep[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce;
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prep, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prep,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values_prep->sum('valor_desgloce_periodo'),
                'valores' => $data_values_prep->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula cronograma produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prod->has($value_2->periodo)) {
                        $periodo = $data_values_prod[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values_prod[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce;
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce;
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prod, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prod,[
                'nombre' => $value->nombre_produccion,
                'total_desgloce_total' => $data_values_prod->sum('valor_desgloce_periodo'),
                'valores' => $data_values_prod->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'periodo_infraestructura' => collect($periodo_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'periodo_preparaciones' => collect($periodo_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'periodo_produccion' => collect($periodo_prod)->sortBy('key')->toArray()
        ];
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

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $ano = $request->ano;

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $periodo_infra = [];
        $periodo_prep = [];
        $periodo_prod = [];
        $mas = 0;
        $array_periodo = [];
        //calcula plan mina infraestructura
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
                        $valor_desgloce_t = $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_infra, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'toneladas' => $value->longitud*$value->area*$value->densidad_esteril,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values->sum('valor_desgloce_periodo'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula plan mina preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prep->has($value_2->periodo)) {
                        $periodo = $data_values_prep[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values_prep[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prep, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prep,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'toneladas' => $value->longitud*$value->area*$value->densidad_esteril,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values_prep->sum('valor_desgloce_periodo'),
                'valores' => $data_values_prep->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula plan mina produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prod->has($value_2->periodo)) {
                        $periodo = $data_values_prod[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values_prod[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prod, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prod,[
                'nombre' => $value->nombre_produccion,
                'total_desgloce_total' => $data_values_prod->sum('valor_desgloce_periodo'),
                'valores' => $data_values_prod->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'periodo_infraestructura' => collect($periodo_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'periodo_preparaciones' => collect($periodo_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'periodo_produccion' => collect($periodo_prod)->sortBy('key')->toArray()
        ];
    }

    /**
     * Search InfraestructuraPeriodo and calculate perforaciones
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_perforaciones_periodo(ShowInfraestructuraPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $id_datos_mina->avance_tronadura;
        $profundidad_tiro = $id_datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $periodo_infra = [];
        $periodo_prep = [];
        $periodo_prod = [];
        $mas = 0;
        $array_periodo = [];
        //calcula perforaciones infraestructura
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values->has($value_2->periodo)) {
                        $periodo = $data_values[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values[$value_2->periodo]['valor_desgloce_periodo'] + ($value_2->valor_desgloce/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => round($valor_desgloce_t),
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = ($value_2->valor_desgloce/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => round($valor_desgloce_t),
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_infra, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'tiros_totales' => round(($value->longitud/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro),
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values->sum('valor_desgloce_periodo'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula perforaciones preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prep->has($value_2->periodo)) {
                        $periodo = $data_values_prep[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values_prep[$value_2->periodo]['valor_desgloce_periodo'] + ($value_2->valor_desgloce/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => round($valor_desgloce_t),
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = ($value_2->valor_desgloce/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => round($valor_desgloce_t),
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prep, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prep,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'tiros_totales' => round(($value->longitud/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro),
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values_prep->sum('valor_desgloce_periodo'),
                'valores' => $data_values_prep->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula perforaciones produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prod->has($value_2->periodo)) {
                        $periodo = $data_values_prod[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values_prod[$value_2->periodo]['valor_desgloce_periodo'] + $value_2->valor_desgloce_perforacion;
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => round($valor_desgloce_t),
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = $value_2->valor_desgloce_perforacion;
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => round($valor_desgloce_t),
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prod, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prod,[
                'nombre' => $value->nombre_produccion,
                'total_desgloce_total' => $data_values_prod->sum('valor_desgloce_periodo'),
                'valores' => $data_values_prod->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'periodo_infraestructura' => collect($periodo_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'periodo_preparaciones' => collect($periodo_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'periodo_produccion' => collect($periodo_prod)->sortBy('key')->toArray()
        ];
    }
/////////////////////////////////////////////////////////////////////////////
    /**
     * Search InfraestructuraPeriodo and calculate tronadura
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function mostrar_tronadura_periodo(ShowInfraestructuraPeriodo $request){
        $id_datos_mina = DatosMina::findBySlug($request->datos_mina);
        $data = $this->repository->queryAll()->
                where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $id_datos_mina->avance_tronadura;
        $profundidad_tiro = $id_datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $periodo_infra = [];
        $periodo_prep = [];
        $periodo_prod = [];
        $mas = 0;
        $array_periodo = [];
        //calcula tronadura infraestructura
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
                        $valor_desgloce_t = ($value_2->valor_desgloce/$avance_tronadura);
                        $data_values->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_infra, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values->sum('valor_desgloce_periodo'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula tronadura preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prep->has($value_2->periodo)) {
                        $periodo = $data_values_prep[$value_2->periodo]['periodo'];
                        $valor_desgloce_t = $data_values_prep[$value_2->periodo]['valor_desgloce_periodo'] + ($value_2->valor_desgloce/$avance_tronadura);
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_t = ($value_2->valor_desgloce/$avance_tronadura);
                        $data_values_prep->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo' => $valor_desgloce_t,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prep, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prep,[
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values_prep->sum('valor_desgloce_periodo'),
                'valores' => $data_values_prep->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $array_periodo = [];
        $mas = 0;
        //calcula tronadura produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($value_2->ano == $ano) {
                    if($data_values_prod->has($value_2->periodo)) {
                        $periodo = $data_values_prod[$value_2->periodo]['periodo'];
                        $valor_desgloce_tron = $data_values_prod[$value_2->periodo]['valor_desgloce_periodo_tronadura'] + $value_2->valor_desgloce_tronadura;
                        $valor_desgloce_car = $data_values_prod[$value_2->periodo]['valor_desgloce_periodo_carguio'] + $value_2->valor_desgloce_carguio;
                        $valor_desgloce_trans = $data_values_prod[$value_2->periodo]['valor_desgloce_periodo_transporte'] + $value_2->valor_desgloce_transporte;
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo_tronadura' => $valor_desgloce_tron,
                            'valor_desgloce_periodo_carguio' => $valor_desgloce_car,
                            'valor_desgloce_periodo_transporte' => $valor_desgloce_trans,
                        ]);
                    } else {
                        $periodo = $value_2->periodo;
                        $valor_desgloce_tron = $value_2->valor_desgloce_tronadura;
                        $valor_desgloce_car = $value_2->valor_desgloce_carguio;
                        $valor_desgloce_trans = $value_2->valor_desgloce_transporte;
                        $data_values_prod->put($periodo, [
                            'periodo' => $periodo,
                            'valor_desgloce_periodo_tronadura' => $valor_desgloce_tron,
                            'valor_desgloce_periodo_carguio' => $valor_desgloce_car,
                            'valor_desgloce_periodo_transporte' => $valor_desgloce_trans,
                        ]);
                        if(!in_array($periodo, $array_periodo)){
                            array_push($periodo_prod, [
                                'key' => $periodo,
                                'label' => 'Periodo '.$periodo,
                            ]);
                            array_push($array_periodo, $periodo);
                            $mas = max($array_periodo);
                        }
                    }
                }
            }
            array_push($data_plan_prod,[
                'nombre' => $value->nombre_produccion,
                'total_desgloce_total_tronadura' => $data_values_prod->sum('valor_desgloce_periodo_tronadura'),
                'total_desgloce_total_carguio' => $data_values_prod->sum('valor_desgloce_periodo_carguio'),
                'total_desgloce_total_transporte' => $data_values_prod->sum('valor_desgloce_periodo_transporte'),
                'valores' => $data_values_prod->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'periodo_infraestructura' => collect($periodo_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'periodo_preparaciones' => collect($periodo_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'periodo_produccion' => collect($periodo_prod)->sortBy('key')->toArray()
        ];
    }
///////////////////////////////////////////////////////////////////////////////////////
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

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $anos_infra = [];
        $anos_prep = [];
        $anos_prod = [];
        $mas = 0;
        $array_ano = [];
        //calcula cronograma infraestructura
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
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_infra, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values->sortBy('ano')->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $array_ano = [];
        $mas = 0;
        //calcula cronograma preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prep->has($value_2->ano)) {
                    $ano = $data_values_prep[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values_prep[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce;
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prep, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prep,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'longitud' => $value->longitud,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_total' => $data_values_prep->sum('valor_desgloce_anual'),
                'valores' => $data_values_prep->sortBy('ano')->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $array_ano = [];
        $mas = 0;
        //calcula cronograma produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prod->has($value_2->ano)) {
                    $ano = $data_values_prod[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values_prod[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prod, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prod,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_produccion,
                'total_desgloce_total' => $data_values_prod->sum('valor_desgloce_anual'),
                'valores' => $data_values_prod->sortBy('ano')->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'anos_infraestructura' => collect($anos_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'anos_preparaciones' => collect($anos_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'anos_produccion' => collect($anos_prod)->sortBy('key')->toArray(),
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

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $id_datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $anos_infra = [];
        $anos_prep = [];
        $anos_prod = [];
        $mas = 0;
        $array_ano = [];
        //plan mina de infraestructura
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
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_infra, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'toneladas' => $value->longitud*$value->area*$value->densidad_esteril,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $array_ano = [];
        $mas = 0;
        //plan mina preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prep->has($value_2->ano)) {
                    $ano = $data_values_prep[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values_prep[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce*($value->area*$value->densidad_esteril);
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t*($value->area*$value->densidad_esteril),
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prep, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prep,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'toneladas' => $value->longitud*$value->area*$value->densidad_esteril,
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values_prep->sum('valor_desgloce_anual'),
                'valores' => $data_values_prep->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $mas = 0;
        //calcula plan mina produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prod->has($value_2->ano)) {
                    $ano = $data_values_prod[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values_prod[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prod, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prod,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_produccion,
                'total_desgloce_anual' => $data_values_prod->sum('valor_desgloce_anual'),
                'valores' => $data_values_prod->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'anos_infraestructura' => collect($anos_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'anos_preparaciones' => collect($anos_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'anos_produccion' => collect($anos_prod)->sortBy('key')->toArray(),
        ];
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

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $datos_mina->avance_tronadura;
        $profundidad_tiro = $datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $anos_infra = [];
        $anos_prep = [];
        $anos_prod = [];
        $mas = 0;
        $array_ano = [];
        //calcular perforaciones infraestructura
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($data_values->has($value_2->ano)) {
                    $ano = $data_values[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values[$value_2->ano]['valor_desgloce_anual'] + ($value_2->valor_desgloce/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => round($valor_desgloce_t),
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => round(($valor_desgloce_t/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro),
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_infra, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'tiros_totales' => round(($value->longitud/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro),
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $array_ano = [];
        $mas = 0;
        //calcular perforaciones preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prep->has($value_2->ano)) {
                    $ano = $data_values_prep[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values_prep[$value_2->ano]['valor_desgloce_anual'] + ($value_2->valor_desgloce/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro;
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => round($valor_desgloce_t),
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => round(($valor_desgloce_t/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro),
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prep, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prep,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'tiros_totales' => round(($value->longitud/$avance_tronadura)*$value->nro_tiros*$profundidad_tiro),
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values_prep->sum('valor_desgloce_anual'),
                'valores' => $data_values_prep->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $array_ano = [];
        $mas = 0;
        //calcula perforaciones produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prod->has($value_2->ano)) {
                    $ano = $data_values_prod[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values_prod[$value_2->ano]['valor_desgloce_anual'] + $value_2->valor_desgloce_perforacion;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce_perforacion;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prod, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prod,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_produccion,
                'total_desgloce_anual' => $data_values_prod->sum('valor_desgloce_anual'),
                'valores' => $data_values_prod->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'anos_infraestructura' => collect($anos_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'anos_preparaciones' => collect($anos_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'anos_produccion' => collect($anos_prod)->sortBy('key')->toArray(),
        ];
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

        $data_preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $data_produccion = CronogramaProduccionPeriodo::where('id_datos_mina', $datos_mina->id)->
                whereNull('deleted_at')->
                get()->load('valores');

        $avance_tronadura = $datos_mina->avance_tronadura;
        $profundidad_tiro = $datos_mina->profundidad_tiro;
        $ano = $request->ano;

        $data_values = collect([]);
        $data_values_prep = collect([]);
        $data_values_prod = collect([]);
        $data_plan = [];
        $data_plan_prep = [];
        $data_plan_prod = [];
        $total_desgloce = 0;
        $anos_infra = [];
        $anos_prep = [];
        $anos_prod = [];
        $mas = 0;
        $array_ano = [];
        //calcular tronadura infraestructura
        foreach($data as $value) {
            foreach($value->valores as $value_2) {
                if($data_values->has($value_2->ano)) {
                    $ano = $data_values[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values[$value_2->ano]['valor_desgloce_anual'] + ($value_2->valor_desgloce/$avance_tronadura);
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => ($value_2->valor_desgloce/$avance_tronadura),
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_infra, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'tronadura' => ($value->longitud/$avance_tronadura),
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values->sum('valor_desgloce_anual'),
                'valores' => $data_values->toArray(),
            ]);
            $data_values = collect([]);
            $total_desgloce = 0;
        }
        $mas = 0;
        $array_ano = [];
        //calcular tronadura preparacion
        foreach($data_preparacion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prep->has($value_2->ano)) {
                    $ano = $data_values_prep[$value_2->ano]['ano'];
                    $valor_desgloce_t = $data_values_prep[$value_2->ano]['valor_desgloce_anual'] + ($value_2->valor_desgloce/$avance_tronadura);
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => $valor_desgloce_t,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_t = $value_2->valor_desgloce;
                    $data_values_prep->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual' => ($valor_desgloce_t/$avance_tronadura),
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prep, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prep,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_infraestructura,
                'seccion' => $value->seccion,
                'area' => $value->area,
                'tronadura' => ($value->longitud/$avance_tronadura),
                'nro_tiros' => $value->nro_tiros,
                'total_desgloce_anual' => $data_values_prep->sum('valor_desgloce_anual'),
                'valores' => $data_values_prep->toArray(),
            ]);
            $data_values_prep = collect([]);
            $total_desgloce = 0;
        }
        $mas = 0;
        //calcula tronadura produccion
        foreach($data_produccion as $value) {
            foreach($value->valores as $value_2) {
                if($data_values_prod->has($value_2->ano)) {
                    $ano = $data_values_prod[$value_2->ano]['ano'];
                    $valor_desgloce_tron = $data_values_prod[$value_2->ano]['valor_desgloce_anual_tronadura'] + $value_2->valor_desgloce_tronadura;
                    $valor_desgloce_car = $data_values_prod[$value_2->ano]['valor_desgloce_anual_carguio'] + $value_2->valor_desgloce_carguio;
                    $valor_desgloce_trans = $data_values_prod[$value_2->ano]['valor_desgloce_anual_transporte'] + $value_2->valor_desgloce_transporte;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual_tronadura' => $valor_desgloce_tron,
                        'valor_desgloce_anual_carguio' => $valor_desgloce_car,
                        'valor_desgloce_anual_transporte' => $valor_desgloce_trans,
                    ]);
                } else {
                    $ano = $value_2->ano;
                    $valor_desgloce_tron = $value_2->valor_desgloce_tronadura;
                    $valor_desgloce_car = $value_2->valor_desgloce_carguio;
                    $valor_desgloce_trans = $value_2->valor_desgloce_transporte;
                    $data_values_prod->put($ano, [
                        'ano' => $ano,
                        'valor_desgloce_anual_tronadura' => $valor_desgloce_tron,
                        'valor_desgloce_anual_carguio' => $valor_desgloce_car,
                        'valor_desgloce_anual_transporte' => $valor_desgloce_trans,
                    ]);
                    if(!in_array($ano, $array_ano)){
                        array_push($anos_prod, [
                            'key' => $ano,
                            'label' => 'Año '.$ano,
                        ]);
                        array_push($array_ano, $ano);
                        $mas = max($array_ano);
                    }
                }
            }
            array_push($data_plan_prod,[
                'slug' => $value->slug,
                'nombre' => $value->nombre_produccion,
                'total_desgloce_anual_tronadura' => $data_values_prod->sum('valor_desgloce_anual_tronadura'),
                'total_desgloce_anual_carguio' => $data_values_prod->sum('valor_desgloce_anual_carguio'),
                'total_desgloce_anual_transporte' => $data_values_prod->sum('valor_desgloce_anual_transporte'),
                'valores' => $data_values_prod->toArray(),
            ]);
            $data_values_prod = collect([]);
            $total_desgloce = 0;
        }

        return [
            'infraestructura' => $data_plan,
            'anos_infraestructura' => collect($anos_infra)->sortBy('key')->toArray(),
            'preparacion' => $data_plan_prep,
            'anos_preparaciones' => collect($anos_prep)->sortBy('key')->toArray(),
            'produccion' => $data_plan_prod,
            'anos_produccion' => collect($anos_prod)->sortBy('key')->toArray(),
        ];
    }

}
