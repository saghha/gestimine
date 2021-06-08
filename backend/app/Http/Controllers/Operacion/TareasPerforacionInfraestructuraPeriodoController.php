<?php

namespace App\Http\Controllers\Operacion;

use App\Models\Operacion\TareasPerforacionInfraestructuraPeriodo;
use App\Models\Operacion\PerforacionInfraestructuraPeriodo;
use App\Repositories\Operacion\PerforacionInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\TareasPerforacionInfraestructuraPeriodoRepository;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\ShowPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\CreatePerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\EditPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\DeletePerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo\ShowTareasPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo\CreateTareasPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo\EditTareasPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo\DeleteTareasPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo\GenericTareasPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo\UpdateTareasPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo\SetTareasPerforacionInfraestructuraPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;
use Illuminate\Http\Request;

class TareasPerforacionInfraestructuraPeriodoController extends Controller
{
    //Se incluye el repositorio de PerforacionInfraestructuraPeriodo
    /** @var TareasPerforacionInfraestructuraPeriodoRepository */
    private $repository;

    public function __construct(TareasPerforacionInfraestructuraPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowTareasPerforacionInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowTareasPerforacionInfraestructuraPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data;
    }

    /**
     * create TareasPerforacionInfraestructuraPeriodo
     * @return TareasPerforacionInfraestructuraPeriodo
     */
    public function store(CreateTareasPerforacionInfraestructuraPeriodo $request){
        $data = $request->validated();
        $data_last = $this->repository->queryAll()->where('id_perforacion',$data['id_perforacion'])->max('orden');
        //return $data_last;
        if($data_last == null){
            $model = $this->repository->create(Arr::only(array_merge($data,["orden" => 1]), $this->repository->attributes()));
        }else{
            $model = $this->repository->create(Arr::only(array_merge($data,["orden" => $data_last + 1]), $this->repository->attributes()));
        }
        return $model;
    }

    /**
     * edit TareasPerforacionInfraestructuraPeriodo
     * @return TareasPerforacionInfraestructuraPeriodo
     */
    public function update(EditTareasPerforacionInfraestructuraPeriodo $request, $slug){
        $data = $request->validated();
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Los datos de la tarea se han actualizado',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * delete TareasPerforacionInfraestructuraPeriodo
     * @return PerforacionInfraestructuraPeriodo
     */
    public function destroy(DeleteTareasPerforacionInfraestructuraPeriodo $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'TareasPerforacionInfraestructuraPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el TareasPerforacionInfraestructuraPeriodo'
        ]);
    }

    /**
     * create generic TareasPerforacionInfraestructuraPeriodo
     * @return GenericTareasPerforacionInfraestructuraPeriodo
     */
    public function generica(GenericTareasPerforacionInfraestructuraPeriodo $request){
        $data = $request->validated();
        $data_last = $this->repository->queryAll()->where('id_perforacion',$data['id_perforacion'])->max('orden');
        //return $data_last;
        $model_array = [];
        $tareas = [
            'PROGRAMACIÓN DE LA MALLA DE PERFORACIÓN Y CARACTERISTICAS DE LOS TIROS A PERFORAR',
            'SELECCIÓN DE LAS HERRAMIENTAS A UTILIZAR',
            'TOPOGRAFIA Y LIMPIEZA',
            'POSICIONAMIENTO DE EQUIPOS DE PERFORACIÓN',
            'PERFORACION',
            'RETIRO Y MUESTREO DE DITRITUS',
            'VERIFICACIÓN DE LA CALIDAD Y CANTIDAD DE TIROS PERFORADOS',
            'INGRESO DE METROS PERFORADOS',
            'RETIRO DE EQUIPOS DE PERFORACION'
        ];
        $count = $data_last;
        $active = true;
        foreach($tareas as $value) {
            //array_push($model_array, $value);
            ++$count;
            if($data_last == null){
                $model = $this->repository->create(Arr::only(array_merge($data,[
                    "orden" => $count,
                    "nombre_tarea" => $value,
                    "termino" => $active,
                    "porcentaje_avance" => "0.00"
                ]), $this->repository->attributes()));
            }else{
                $model = $this->repository->create(Arr::only(array_merge($data,[
                    "orden" => $count,
                    "nombre_tarea" => $value,
                    "termino" => $active,
                    "porcentaje_avance" => "0.00"
                ]), $this->repository->attributes()));
            }
            $active = false;
        }
        return $this->repository->queryAll()->where('id_perforacion',$data['id_perforacion'])->get();
    }

    /**
     * get paged last data work of ShowTareasPerforacionInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function tarea_activa(ShowTareasPerforacionInfraestructuraPeriodo $request, $slug){
        $perforacion = PerforacionInfraestructuraPeriodo::findBySlugOrFail($slug);
        $data_last = $this->repository->queryAll()->where('id_perforacion',$perforacion->id)->where('termino', true)->orderBy('orden')->first();
        return $data_last;
    }

    /**
     * get paged last data work of ShowTareasPerforacionInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function editar_tarea_activa(UpdateTareasPerforacionInfraestructuraPeriodo $request){
        $perforacion = PerforacionInfraestructuraPeriodo::find($request->id_perforacion);
        $data_last = $this->repository->queryAll()->where('id_perforacion',$perforacion->id)->where('termino', true)->orderBy('orden')->first();
        if(!$data_last) throw new RuntimeException("Debe generar nuevas tareas de perforacion");
        $data_last->porcentaje_avance = $request->porcentaje_avance;
        $data_last->save();
        if($data_last->porcentaje_avance == 1 && $data_last->termino){
            $data_last->termino = false;
            $data_last->save();
            $data_new = $this->repository->queryAll()->where('id_perforacion',$perforacion->id)->where('porcentaje_avance', 0)->where('termino', false)->orderBy('orden')->first();
            if(!$data_new) throw new RuntimeException("No existen mas tareas de porforacion pendientes");
            $data_new->termino = true;
            $data_new->save();
            $data_last = $data_new;
        }
        return $data_last;
    }

    /**
     * set first data work of SetTareasPerforacionInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function reiniciar_tarea_activa(SetTareasPerforacionInfraestructuraPeriodo $request){
        $perforacion = PerforacionInfraestructuraPeriodo::find($request->id_perforacion);
        $data_last = $this->repository->queryAll()->where('id_perforacion',$perforacion->id)->orderBy('orden')->get();
        foreach($data_last as $value) {
            if($value->termino == true) throw new RuntimeException("Existe una tarea activa en esta iteracion");
        }
        $data_refresh = $this->repository->queryAll()->where('id_perforacion',$perforacion->id)->orderBy('orden')->get();
        foreach($data_refresh as $value) {
            if($value->porcentaje_avance <= 1) $value->porcentaje_avance = 0;
            $value->save();
        }
        $data_new = $this->repository->queryAll()->where('id_perforacion',$perforacion->id)->orderBy('orden')->first();
        $data_new->termino = true;
        $data_new->save();
        return $data_new;
    }
}
