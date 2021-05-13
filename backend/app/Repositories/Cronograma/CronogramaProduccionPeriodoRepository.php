<?php

namespace App\Repositories\Cronograma;

use App\Repositories\RepositoryInterface;
use App\Models\Cronograma\CronogramaProduccionPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class CronogramaProduccionPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param CronogramaProduccionPeriodo $cronograma_preparacion_periodo
     */
    public function __construct(CronogramaProduccionPeriodo $cronograma_preparacion_periodo){
        $this->model = $cronograma_preparacion_periodo;
    }
}