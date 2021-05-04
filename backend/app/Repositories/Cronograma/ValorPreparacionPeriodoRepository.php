<?php

namespace App\Repositories\Cronograma;

use App\Repositories\RepositoryInterface;
use App\Models\Cronograma\ValorPreparacionPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class ValorPreparacionPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param ValorPreparacionPeriodo $valor_preparacion_periodo
     */
    public function __construct(ValorPreparacionPeriodo $valor_preparacion_periodo){
        $this->model = $valor_preparacion_periodo;
    }
}