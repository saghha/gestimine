<?php

namespace App\Repositories\Operacion;

use App\Repositories\RepositoryInterface;
use App\Models\Operacion\CarguioInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class CarguioInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param CarguioInfraestructuraPeriodo $carguio_infraestructura_periodo
     */
    public function __construct(CarguioInfraestructuraPeriodo $carguio_infraestructura_periodo){
        $this->model = $carguio_infraestructura_periodo;
    }
}