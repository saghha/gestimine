<?php

namespace App\Repositories\Cronograma;

use App\Repositories\RepositoryInterface;
use App\Models\Cronograma\ValorProduccionPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class ValorProduccionPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param ValorProduccionPeriodo $valor_produccion_periodo
     */
    public function __construct(ValorProduccionPeriodo $valor_produccion_periodo){
        $this->model = $valor_produccion_periodo;
    }
}