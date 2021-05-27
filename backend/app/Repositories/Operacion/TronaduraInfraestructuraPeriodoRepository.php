<?php

namespace App\Repositories\Operacion;

use App\Repositories\RepositoryInterface;
use App\Models\Operacion\TronaduraInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class TronaduraInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param TronaduraInfraestructuraPeriodo $tronadura_infraestructura_periodo
     */
    public function __construct(TronaduraInfraestructuraPeriodo $tronadura_infraestructura_periodo){
        $this->model = $tronadura_infraestructura_periodo;
    }
}