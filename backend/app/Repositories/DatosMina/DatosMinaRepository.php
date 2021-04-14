<?php

namespace App\Repositories\DatosMina;

use App\Repositories\RepositoryInterface;
use App\Models\DatosMina\DatosMina;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class DatosMinaRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param DatosMina $datosmina
     */
    public function __construct(DatosMina $datosmina){
        $this->model = $datosmina;
    }
}