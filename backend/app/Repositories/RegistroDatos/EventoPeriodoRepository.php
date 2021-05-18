<?php

namespace App\Repositories\RegistroDatos;

use App\Repositories\RepositoryInterface;
use App\Models\RegistroDatos\EventoPeriodo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use App\Repositories\Traits\BasicCrudOperations;

class EventoPeriodoRepository
{
    use BasicCrudOperations;
    /**
     * The constructor of the Repository
     * @param EventoPeriodo $eventoperiodo
     */
    public function __construct(EventoPeriodo $eventoperiodo){
        $this->model = $eventoperiodo;
    }
}