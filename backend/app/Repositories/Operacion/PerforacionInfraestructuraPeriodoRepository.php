<?php

namespace App\Repositories\Operacion;

use App\Repositories\RepositoryInterface;
use App\Models\Operacion\PerforacionInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class PerforacionInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param PerforacionInfraestructuraPeriodo $perforacion_infraestructura_periodo
     */
    public function __construct(PerforacionInfraestructuraPeriodo $perforacion_infraestructura_periodo){
        $this->model = $perforacion_infraestructura_periodo;
    }
}