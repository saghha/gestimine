<?php

namespace App\Http\Controllers\DatosMina;

use Illuminate\Http\Request;
use App\Repositories\DatosMina\DatosMinaRepository;
use App\Http\Requests\DatosMina\ShowDatosMina;
use App\Http\Requests\DatosMina\CreateDatosMina;
use App\Http\Requests\DatosMina\EditDatosMina;
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

}
