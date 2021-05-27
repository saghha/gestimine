<?php

namespace App\Http\Controllers\Operacion;

use App\Models\Operacion\TareasTronaduraInfraestructuraPeriodo;
use App\Models\Operacion\TronaduraInfraestructuraPeriodo;
use App\Repositories\Operacion\TronaduraInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\TareasTronaduraInfraestructuraPeriodoRepository;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\ShowTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\CreateTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\EditTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\DeleteTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo\ShowTareasTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo\CreateTareasTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo\EditTareasTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo\DeleteTareasTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo\GenericTareasTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo\UpdateTareasTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo\SetTareasTronaduraInfraestructuraPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;
use Illuminate\Http\Request;

class TareasTronaduraInfraestructuraPeriodoController extends Controller
{
    //Se incluye el repositorio de TronaduraInfraestructuraPeriodo
    /** @var TareasTronaduraInfraestructuraPeriodoRepository */
    private $repository;

    public function __construct(TareasTronaduraInfraestructuraPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowTareasTronaduraInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowTareasTronaduraInfraestructuraPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data;
    }

    /**
     * create TareasTronaduraInfraestructuraPeriodo
     * @return TareasTronaduraInfraestructuraPeriodo
     */
    public function store(CreateTareasTronaduraInfraestructuraPeriodo $request){
        $data = $request->validated();
        $data_last = $this->repository->queryAll()->where('id_tronadura',$data['id_tronadura'])->max('orden');
        //return $data_last;
        if($data_last == null){
            $model = $this->repository->create(Arr::only(array_merge($data,["orden" => 1]), $this->repository->attributes()));
        }else{
            $model = $this->repository->create(Arr::only(array_merge($data,["orden" => $data_last + 1]), $this->repository->attributes()));
        }
        return $model;
    }

    /**
     * edit TareasTronaduraInfraestructuraPeriodo
     * @return TareasTronaduraInfraestructuraPeriodo
     */
    public function update(EditTareasTronaduraInfraestructuraPeriodo $request, $slug){
        $data = $request->validated();
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
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
     * delete TareasTronaduraInfraestructuraPeriodo
     * @return TronaduraInfraestructuraPeriodo
     */
    public function destroy(DeleteTareasTronaduraInfraestructuraPeriodo $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'TareasTronaduraInfraestructuraPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el TareasTronaduraInfraestructuraPeriodo'
        ]);
    }

    /**
     * create generic TareasTronaduraInfraestructuraPeriodo
     * @return GenericTareasTronaduraInfraestructuraPeriodo
     */
    public function generica(GenericTareasTronaduraInfraestructuraPeriodo $request){
        $data = $request->validated();
        $data_last = $this->repository->queryAll()->where('id_tronadura',$data['id_tronadura'])->max('orden');
        //return $data_last;
        $model_array = [];
        $tareas = [
            'AISLAMIENTO DEL SECTOR',
            'POSICIONAMIENTO DE EQUIPOS DE CARGUIO DE EXPLOSIVOS',
            'CONTROL DE CALIDAD DEL EXPLOSIVO',
            'INTRODUCCION DEL EXPLOSIVO EN LOS TIROS Y LOS ACCESORIOS NESESARIOS',
            'ENTACADO DE TIROS',
            'AMARRE SEGUN SECUENCIA DE DETONACION ESPECIFICADA',
            'REVISION DE SEGURIDAD DE LOS SECTORES',
            'RETIRO DE EQUIPOS DE TRONADURA',
            'PRIMER AVISO',
            'AVISOS POSTERIORES Y ULTIMO AVISO',
            'TRONADURA',
            'VENTILACION',
            'REVISION DE SEGURIDAD DE LOS TIROS',
            'QUEMA DE LOS TIROS REZAGADOS',
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
        return $this->repository->queryAll()->where('id_tronadura',$data['id_tronadura'])->get();
    }

    /**
     * get paged last data work of ShowTareasTronaduraInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function tarea_activa(ShowTareasTronaduraInfraestructuraPeriodo $request, $slug){
        $tronadura = TronaduraInfraestructuraPeriodo::findBySlugOrFail($slug);
        $data_last = $this->repository->queryAll()->where('id_tronadura',$tronadura->id)->where('termino', true)->orderBy('orden')->first();
        return $data_last;
    }

    /**
     * get paged last data work of ShowTareasTronaduraInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function editar_tarea_activa(UpdateTareasTronaduraInfraestructuraPeriodo $request){
        $tronadura = TronaduraInfraestructuraPeriodo::find($request->id_tronadura);
        $data_last = $this->repository->queryAll()->where('id_tronadura',$tronadura->id)->where('termino', true)->orderBy('orden')->first();
        if(!$data_last) throw new RuntimeException("Debe generar nuevas tareas de tronadura");
        $data_last->porcentaje_avance = $request->porcentaje_avance;
        $data_last->save();
        if($data_last->porcentaje_avance == 1 && $data_last->termino){
            $data_last->termino = false;
            $data_last->save();
            $data_new = $this->repository->queryAll()->where('id_tronadura',$tronadura->id)->where('porcentaje_avance', 0)->where('termino', false)->orderBy('orden')->first();
            if(!$data_new) throw new RuntimeException("No existen mas tareas de porforacion pendientes");
            $data_new->termino = true;
            $data_new->save();
            $data_last = $data_new;
        }
        return $data_last;
    }

    /**
     * set first data work of SetTareasTronaduraInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function reiniciar_tarea_activa(SetTareasTronaduraInfraestructuraPeriodo $request){
        $tronadura = TronaduraInfraestructuraPeriodo::find($request->id_tronadura);
        $data_last = $this->repository->queryAll()->where('id_tronadura',$tronadura->id)->orderBy('orden')->get();
        foreach($data_last as $value) {
            if($value->termino == true) throw new RuntimeException("Existe una tarea activa en esta iteracion");
        }
        $data_refresh = $this->repository->queryAll()->where('id_tronadura',$tronadura->id)->orderBy('orden')->get();
        foreach($data_refresh as $value) {
            if($value->porcentaje_avance <= 1) $value->porcentaje_avance = 0;
            $value->save();
        }
        $data_new = $this->repository->queryAll()->where('id_tronadura',$tronadura->id)->orderBy('orden')->first();
        $data_new->termino = true;
        $data_new->save();
        return $data_new;
    }
}
