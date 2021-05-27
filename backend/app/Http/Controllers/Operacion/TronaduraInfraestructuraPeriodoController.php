<?php

namespace App\Http\Controllers\Operacion;

use App\Models\DatosMina\DatosMina;
use App\Models\Operacion\TronaduraInfraestructuraPeriodo;
use App\Models\Cronograma\CronogramaInfraestructuraPeriodo;
use App\Repositories\Cronograma\CronogramaInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\TronaduraInfraestructuraPeriodoRepository;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\ShowTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\CreateTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\EditTronaduraInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo\DeleteTronaduraInfraestructuraPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;

class TronaduraInfraestructuraPeriodoController extends Controller
{
    //Se incluye el repositorio de TronaduraInfraestructuraPeriodo
    /** @var TronaduraInfraestructuraPeriodoRepository */
    private $repository;

    public function __construct(TronaduraInfraestructuraPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowCronogramaInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowTronaduraInfraestructuraPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data->load('infraestructura');
    }

    /**
     * create TronaduraInfraestructuraPeriodo
     * @return TronaduraInfraestructuraPeriodo
     */
    public function store(CreateTronaduraInfraestructuraPeriodo $request){
        $data = $request->validated();
        $model = $this->repository->create(Arr::only($data, $this->repository->attributes()));
        return $model;
    }
    
    /**
     * edit TronaduraInfraestructuraPeriodo
     * @return TronaduraInfraestructuraPeriodo
     */
    public function update(EditTronaduraInfraestructuraPeriodo $request, $slug){
        $data = $request->validated();
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Los datos de la tronadura se han actualizado',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * delete TronaduraInfraestructuraPeriodo
     * @return TronaduraInfraestructuraPeriodo
     */
    public function destroy(DeleteTronaduraInfraestructuraPeriodo $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'TronaduraInfraestructuraPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el TronaduraInfraestructuraPeriodo'
        ]);
    }

    /**
     * get paged index of ShowCronogramaInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscar(ShowTronaduraInfraestructuraPeriodo $request){
        $datos_mina = DatosMina::findBySlugOrFail($request->id_datos_mina);
        $data = $this->repository->queryAll()->whereHas('infraestructura', function($infraestructura) use ($datos_mina) {
            $infraestructura->where('id_datos_mina', $datos_mina->id);
        })->where('periodo',$request->periodo)->where('ano',$request->ano)->get();
        return $data->load(['infraestructura','tareas']);
    }
}
