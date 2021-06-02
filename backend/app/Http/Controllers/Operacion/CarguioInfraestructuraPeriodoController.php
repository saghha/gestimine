<?php

namespace App\Http\Controllers\Operacion;

use App\Models\DatosMina\DatosMina;
use App\Models\Operacion\CarguioInfraestructuraPeriodo;
use App\Models\Cronograma\CronogramaInfraestructuraPeriodo;
use App\Repositories\Cronograma\CronogramaInfraestructuraPeriodoRepository;
use App\Repositories\Operacion\CarguioInfraestructuraPeriodoRepository;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\ShowCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\CreateCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\EditCarguioInfraestructuraPeriodo;
use App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo\DeleteCarguioInfraestructuraPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;
use Illuminate\Http\Request;

class CarguioInfraestructuraPeriodoController extends Controller
{
    //Se incluye el repositorio de CarguioInfraestructuraPeriodo
    /** @var CarguioInfraestructuraPeriodoRepository */
    private $repository;

    public function __construct(CarguioInfraestructuraPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowCarguioInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowCarguioInfraestructuraPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data->load('infraestructura');
    }

    /**
     * create CarguioInfraestructuraPeriodo
     * @return CarguioInfraestructuraPeriodo
     */
    public function store(CreateCarguioInfraestructuraPeriodo $request){
        $data = $request->validated();
        $model = $this->repository->create(Arr::only($data, $this->repository->attributes()));
        return $model;
    }

    /**
     * edit CarguioInfraestructuraPeriodo
     * @return CarguioInfraestructuraPeriodo
     */
    public function update(EditCarguioInfraestructuraPeriodo $request, $slug){
        $data = $request->validated();
        if($this->repository->edit(Arr::only($data, $this->repository->attributes()), $slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Los datos de carguio y transporte se han actualizado',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * delete CarguioInfraestructuraPeriodo
     * @return CarguioInfraestructuraPeriodo
     */
    public function destroy(DeleteCarguioInfraestructuraPeriodo $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'CarguioInfraestructuraPeriodo eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el CarguioInfraestructuraPeriodo'
        ]);
    }

    /**
     * get paged index of ShowCarguioInfraestructuraPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscar(ShowCarguioInfraestructuraPeriodo $request){
        $datos_mina = DatosMina::findBySlugOrFail($request->id_datos_mina);
        $data = $this->repository->queryAll()->whereHas('infraestructura', function($infraestructura) use ($datos_mina) {
            $infraestructura->where('id_datos_mina', $datos_mina->id);
        })->where('periodo',$request->periodo)->where('ano',$request->ano)->get();
        return $data->load(['infraestructura','tareas']);
    }
}
