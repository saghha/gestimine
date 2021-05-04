<?php

namespace App\Repositories\Cronograma;

use App\Repositories\RepositoryInterface;
use App\Models\Cronograma\CronogramaInfraestructuraPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class CronogramaInfraestructuraPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param CronogramaInfraestructuraPeriodo $cronograma_infraestructura_periodo
     */
    public function __construct(CronogramaInfraestructuraPeriodo $cronograma_infraestructura_periodo){
        $this->model = $cronograma_infraestructura_periodo;
    }
}