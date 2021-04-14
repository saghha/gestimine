<?php

namespace App\Http\Controllers\DatosMina;

use Illuminate\Http\Request;
use App\Repositories\DatosMina\DatosMinaRepository;
use App\Http\Requests\DatosMina\ShowDatosMina;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;

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
        //return $this->repository->queryAll()->paginate(20);
        return "hola";
    }
}
