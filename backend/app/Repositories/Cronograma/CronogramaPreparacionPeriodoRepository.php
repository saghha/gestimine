<?php

namespace App\Repositories\Cronograma;

use App\Repositories\RepositoryInterface;
use App\Models\Cronograma\CronogramaPreparacionPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class CronogramaPreparacionPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param CronogramaPreparacionPeriodo $cronograma_preparacion_periodo
     */
    public function __construct(CronogramaPreparacionPeriodo $cronograma_preparacion_periodo){
        $this->model = $cronograma_preparacion_periodo;
    }
}