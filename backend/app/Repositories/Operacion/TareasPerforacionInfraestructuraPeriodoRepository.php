<?php

namespace App\Repositories\Operacion;

use App\Repositories\RepositoryInterface;
use App\Models\Operacion\TareasPerforacionInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class TareasPerforacionInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param TareasPerforacionInfraestructuraPeriodo $tareas_perforacion_infraestructura_periodo
     */
    public function __construct(TareasPerforacionInfraestructuraPeriodo $tareas_perforacion_infraestructura_periodo){
        $this->model = $tareas_perforacion_infraestructura_periodo;
    }
}