<?php

namespace App\Repositories\Operacion;

use App\Repositories\RepositoryInterface;
use App\Models\Operacion\TareasTronaduraInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class TareasTronaduraInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param TareasTronaduraInfraestructuraPeriodo $tareas_tronadura_infraestructura_periodo
     */
    public function __construct(TareasTronaduraInfraestructuraPeriodo $tareas_tronadura_infraestructura_periodo){
        $this->model = $tareas_tronadura_infraestructura_periodo;
    }
}