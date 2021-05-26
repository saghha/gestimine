<?php

namespace App\Http\Controllers\DatosMina;

use Illuminate\Http\Request;
use App\Repositories\DatosMina\DatosMinaRepository;
use App\Http\Requests\DatosMina\ShowDatosMina;
use App\Http\Requests\DatosMina\CreateDatosMina;
use App\Http\Requests\DatosMina\EditDatosMina;
use App\Http\Requests\DatosMina\DeleteDatosMina;
use App\Http\Requests\DatosMina\DuplicateDatosMina;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;

class DatosMinaController extends Controller
{
    //Se incluye el repositorio de datosMina
    /** @var DatosMinaRepository */
    private $repository;

    public function __construct(DatosMinaRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of DatosMina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowDatosMina $request){
        return $this->repository->queryAll()->where('id_usuario', $request->user()->id)->get();
    }

    /**
     * create DatosMina
     * @return DatosMina
     */
    public function store(CreateDatosMina $request){
        $data = $request->validated();
        $model = $this->repository->create(Arr::only(array_merge($data, ['id_usuario' => $request->user()->id]), $this->repository->attributes()));
        return $model;
    }

    /**
     * delete DatosMina
     * @return DatosMina
     */
    public function destroy(DeleteDatosMina $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'DatosMina eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el DatosMina'
        ]);
    }

    /**
     * edit DatosMina
     * @return DatosMina
     */
    public function update(EditDatosMina $request, $slug){
        $data = $request->validated();
        if($this->repository->edit(Arr::only(array_merge($data, ['id_usuario' => $request->user()->id]), $this->repository->attributes()), $slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Los datos de la mina se han actualizado',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * duplicar DatosMina
     * @return DatosMina
     */
    public function duplicar(DuplicateDatosMina $request){
        $fecha = Arr::get($request, 'fecha_estado', now()->firstOfYear());
        $fecha_year = new Carbon($fecha);
        $model = $this->repository->queryAll()->where('id_usuario', $request->user()->id)->
                    whereNull('deleted_at')->latest()->first();
        if($model->periodo >= $model->periodo_por_ano) throw new RuntimeException("No se puede crear otro periodo, debe editar el periodo por año en el ultimo ingreso de datos mina");
        //if(!($model->periodo == $model->periodo_por_ano) && !($model->ano == $fecha_year->year)) throw new RuntimeException("No se puede crear un periodo para el proximo año, debe completarse los periodos de este año");

        $data = [
            "periodo_por_ano" => $model->periodo_por_ano,
            "ano" => $fecha_year->year,
            "periodo" => $fecha_year->year != $model->ano ? 1 : $model->periodo + 1,
            "meses_por_periodo" => $model->meses_por_periodo,
            "dias_por_mes" => $model->dias_por_mes,
            "turnos_por_dia" => $model->turnos_por_dia,
            "fecha_inicio" => $fecha_year,
            "avance_tronadura" => $model->avance_tronadura,
            "toneladas_incorporadas_tronadura" => $model->toneladas_incorporadas_tronadura,
            "ritmo_extraccion" => $model->ritmo_extraccion,
            "mineral_recuperado_modulo" => $model->mineral_recuperado_modulo,
            "mineral_recuperado_pilares" => $model->mineral_recuperado_pilares,
            "densidad_esteril" => $model->densidad_esteril,
            "densidad_mineral" => $model->densidad_mineral,
            "densidad_dilusion" => $model->densidad_dilusion,
            "ley_esteril" => $model->ley_esteril,
            "ley_mineral" => $model->ley_mineral,
            "ley_diluida" => $model->ley_diluida
        ];

        $model = $this->repository->create(Arr::only(array_merge($data, ['id_usuario' => $request->user()->id]), $this->repository->attributes()));
        return $model;
    }

    /**
     * get last DatosMina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function ultimo(ShowDatosMina $request){
        return $this->repository->queryAll()->where('id_usuario', $request->user()->id)->whereNull('deleted_at')->latest()->first();
    }

    /**
     * Search DatosMina
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscar(ShowDatosMina $request){
        return $this->repository->queryAll()->
                where('id_usuario', $request->user()->id)->
                where('periodo', $request->periodo)->
                where('ano', $request->ano)->
                whereNull('deleted_at')->
                latest()->first();
    }

    /**
     * get last DatosMina periodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function periodo(ShowDatosMina $request){
        $mina = $this->repository->queryAll()->where('id_usuario', $request->user()->id)->whereNull('deleted_at')->latest()->first();
        switch ($mina->meses_por_periodo) {
            case 1:
                $periodo = "MES";
                break;
            case 2:
                $periodo = "BIMESTRE";
                break;
            case 3:
                $periodo = "TRIMESTRE";
                break;
            case 4:
                $periodo = "CUATRIMESTRE";
                break;
            case 6:
                $periodo = "SEMESTRE";
                break;
            case 12:
                $periodo = "AÑO";
                break;
            default:
                $periodo = "PERIODO DE ".$mina->meses_por_periodo." MESES";
                break;
        }
        $tiempo_inicial = Carbon::createFromFormat('Y-m-d H:i:s', $mina->fecha_inicio);
        $tiempo_actual = Carbon::now();
        $periodo_actual = floor(($tiempo_inicial->diffInMonths($tiempo_actual))/$mina->meses_por_periodo) + 1;
        $resp = [
            "ano" => $mina->ano,
            "rango_periodo" => $mina->meses_por_periodo,
            "referencia" => $periodo,
            "periodo_actual" => $periodo_actual
        ];
        return $resp;
    }

}
