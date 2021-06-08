<?php

namespace App\Http\Controllers\Operacion;

use App\Models\Operacion\TareasCarguioInfraestructuraPeriodo;
use App\Models\Operacion\CarguioInfraestructuraPeriodo;
use App\Repositories\Operacion\CarguioInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\TareasCarguioInfraestructuraPeriodoRepository;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\ShowCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\CreateCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\EditCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\DeleteCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo\ShowTareasCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo\CreateTareasCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo\EditTareasCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo\DeleteTareasCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo\GenericTareasCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo\UpdateTareasCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo\SetTareasCarguioInfraestructuraPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;
use Illuminate\Http\Request;

class TareasCarguioInfraestructuraPeriodoController extends Controller
{
    //Se incluye el repositorio de CarguioInfraestructuraPeriodo
    /** @var TareasCarguioInfraestructuraPeriodoRepository */
    private $repository;

    public function __construct(TareasCarguioInfraestructuraPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowTareasCarguioInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowTareasCarguioInfraestructuraPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data;
    }

    /**
     * create TareasCarguioInfraestructuraPeriodo
     * @return TareasCarguioInfraestructuraPeriodo
     */
    public function store(CreateTareasCarguioInfraestructuraPeriodo $request){
        $data = $request->validated();
        $data_last = $this->repository->queryAll()->where('id_carguio',$data['id_carguio'])->max('orden');
        //return $data_last;
        if($data_last == null){
            $model = $this->repository->create(Arr::only(array_merge($data,["orden" => 1]), $this->repository->attributes()));
        }else{
            $model = $this->repository->create(Arr::only(array_merge($data,["orden" => $data_last + 1]), $this->repository->attributes()));
        }
        return $model;
    }

    /**
     * edit TareasCarguioInfraestructuraPeriodo
     * @return TareasCarguioInfraestructuraPeriodo
     */
    public function update(EditTareasCarguioInfraestructuraPeriodo $request, $slug){
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
     * delete TareasCarguioInfraestructuraPeriodo
     * @return CarguioInfraestructuraPeriodo
     */
    public function destroy(DeleteTareasCarguioInfraestructuraPeriodo $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'TareasCarguioInfraestructuraPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el TareasCarguioInfraestructuraPeriodo'
        ]);
    }

    /**
     * create generic TareasCarguioInfraestructuraPeriodo
     * @return GenericTareasCarguioInfraestructuraPeriodo
     */
    public function generica(GenericTareasCarguioInfraestructuraPeriodo $request){
        $data = $request->validated();
        $data_last = $this->repository->queryAll()->where('id_carguio',$data['id_carguio'])->max('orden');
        //return $data_last;
        $model_array = [];
        $tareas = [
            'PREPARACION DE LA ZONA DE TRABAJO',
            'POCISIONAMIENTO DE EQUIPOS DE CARGUIO Y TRANSPORTE',
            'CARGUIO Y TRANSPORTE',
            'RETIRO DE EQUIPOS DE CARGUIO Y TRANSPORTE',
            'REGISTRO DE AVANCE REAL',
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
        return $this->repository->queryAll()->where('id_carguio',$data['id_carguio'])->get();
    }

    /**
     * get paged last data work of ShowTareasCarguioInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function tarea_activa(ShowTareasCarguioInfraestructuraPeriodo $request, $slug){
        $carguio = CarguioInfraestructuraPeriodo::findBySlugOrFail($slug);
        $data_last = $this->repository->queryAll()->where('id_carguio',$carguio->id)->where('termino', true)->orderBy('orden')->first();
        return $data_last;
    }

    /**
     * get paged last data work of ShowTareasCarguioInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function editar_tarea_activa(UpdateTareasCarguioInfraestructuraPeriodo $request){
        $carguio = CarguioInfraestructuraPeriodo::find($request->id_carguio);
        $data_last = $this->repository->queryAll()->where('id_carguio',$carguio->id)->where('termino', true)->orderBy('orden')->first();
        if(!$data_last) throw new RuntimeException("Debe generar nuevas tareas de carguio");
        $data_last->porcentaje_avance = $request->porcentaje_avance;
        $data_last->save();
        if($data_last->porcentaje_avance == 1 && $data_last->termino){
            $data_last->termino = false;
            $data_last->save();
            $data_new = $this->repository->queryAll()->where('id_carguio',$carguio->id)->where('porcentaje_avance', 0)->where('termino', false)->orderBy('orden')->first();
            if(!$data_new) throw new RuntimeException("No existen mas tareas de porforacion pendientes");
            $data_new->termino = true;
            $data_new->save();
            $data_last = $data_new;
        }
        return $data_last;
    }

    /**
     * set first data work of SetTareasCarguioInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function reiniciar_tarea_activa(SetTareasCarguioInfraestructuraPeriodo $request){
        $carguio = CarguioInfraestructuraPeriodo::find($request->id_carguio);
        $data_last = $this->repository->queryAll()->where('id_carguio',$carguio->id)->orderBy('orden')->get();
        foreach($data_last as $value) {
            if($value->termino == true) throw new RuntimeException("Existe una tarea activa en esta iteracion");
        }
        $data_refresh = $this->repository->queryAll()->where('id_carguio',$carguio->id)->orderBy('orden')->get();
        foreach($data_refresh as $value) {
            if($value->porcentaje_avance <= 1) $value->porcentaje_avance = 0;
            $value->save();
        }
        $data_new = $this->repository->queryAll()->where('id_carguio',$carguio->id)->orderBy('orden')->first();
        $data_new->termino = true;
        $data_new->save();
        return $data_new;
    }
}
