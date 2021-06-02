<?php

namespace App\Repositories\Operacion;

use App\Repositories\RepositoryInterface;
use App\Models\Operacion\TareasCarguioInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class TareasCarguioInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param TareasCarguioInfraestructuraPeriodo $tareas_carguio_infraestructura_periodo
     */
    public function __construct(TareasCarguioInfraestructuraPeriodo $tareas_carguio_infraestructura_periodo){
        $this->model = $tareas_carguio_infraestructura_periodo;
    }
}