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
}
