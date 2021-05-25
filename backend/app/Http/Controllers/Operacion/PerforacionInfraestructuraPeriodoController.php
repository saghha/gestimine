<?php

namespace App\Http\Controllers\Operacion;

use App\Models\Operacion\PerforacionInfraestructuraPeriodo;
use App\Models\Cronograma\CronogramaInfraestructuraPeriodo;
use App\Repositories\Cronograma\CronogramaInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\PerforacionInfraestructuraPeriodoRepository;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\ShowPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\CreatePerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\EditPerforacionInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo\DeletePerforacionInfraestructuraPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;

class PerforacionInfraestructuraPeriodoController extends Controller
{
    //Se incluye el repositorio de PerforacionInfraestructuraPeriodo
    /** @var PerforacionInfraestructuraPeriodoRepository */
    private $repository;

    public function __construct(PerforacionInfraestructuraPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowCronogramaInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowPerforacionInfraestructuraPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data;
    }

    /**
     * create PerforacionInfraestructuraPeriodo
     * @return PerforacionInfraestructuraPeriodo
     */
    public function store(CreatePerforacionInfraestructuraPeriodo $request){
        $data = $request->validated();
        $model = $this->repository->create(Arr::only($data, $this->repository->attributes()));
        return $model;
    }
    
    /**
     * edit PerforacionInfraestructuraPeriodo
     * @return PerforacionInfraestructuraPeriodo
     */
    public function update(EditPerforacionInfraestructuraPeriodo $request, $slug){
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
     * delete PerforacionInfraestructuraPeriodo
     * @return PerforacionInfraestructuraPeriodo
     */
    public function destroy(DeletePerforacionInfraestructuraPeriodo $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'PerforacionInfraestructuraPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el PerforacionInfraestructuraPeriodo'
        ]);
    }
    
}
