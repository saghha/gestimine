<?php

namespace App\Repositories\Cronograma;

use App\Repositories\RepositoryInterface;
use App\Models\Cronograma\ValorInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class ValorInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param ValorInfraestructuraPeriodo $valor_infraestructura_periodo
     */
    public function __construct(ValorInfraestructuraPeriodo $valor_infraestructura_periodo){
        $this->model = $valor_infraestructura_periodo;
    }
}