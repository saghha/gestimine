<?php

namespace App\Http\Controllers\RegistroDatos;

use Illuminate\Http\Request;
use App\Models\RegistroDatos\EventoPeriodo;
use App\Repositories\RegistroDatos\EventoPeriodoRepository;
use App\Http\Requests\RegistroDatos\EventoPeriodo\ShowEventoPeriodo;
use App\Http\Requests\RegistroDatos\EventoPeriodo\CreateEventoPeriodo;
use App\Http\Requests\RegistroDatos\EventoPeriodo\EditEventoPeriodo;
use App\Http\Requests\RegistroDatos\EventoPeriodo\DeleteEventoPeriodo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;
use Mail;
use App\Mail\EmergencyCallReceived;

class EventoPeriodoController extends Controller
{
    //Se incluye el repositorio de EventoPeriodo
    /** @var EventoPeriodoRepository */
    private $repository;

    public function __construct(EventoPeriodoRepository $repository){
        $this->repository = $repository;
    }

    /**
     * get paged index of ShowEventoPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(ShowEventoPeriodo $request){
        $data = $this->repository->queryAll()->get();
        return $data;
    }

    /**
     * create EventoPeriodo
     * @return EventoPeriodo
     */
    public function store(CreateEventoPeriodo $request){
        $data = $request->validated();
        $model = $this->repository->create(Arr::only(array_merge($data, ['id_usuario' => $request->user()->id]), $this->repository->attributes()));
        $array = [
            'name' => $request->user()->name,
        ];
        $subject = "Notificacion de evento";
        $for = ["mauricio.aros@alumnos.usm.cl"];
        Mail::mailer('smtp')->send('mails.emergency_call',array_merge($array,$request->all()), function($msj) use($subject,$for){
            $msj->from("mantos.cerro.verde.minera@gmail.com", "Emision Automatica de emergencia");
            $msj->subject($subject);
            $msj->to($for);
        });

        return $model;
    }

    /**
     * edit EventoPeriodo
     * @return EventoPeriodo
     */
    public function update(EditEventoPeriodo $request, $slug){
        $data = $request->validated();
        if($this->repository->edit(Arr::only(array_merge($data, ['id_usuario' => $request->user()->id]), $this->repository->attributes()), $slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Los datos del evento se han actualizado',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado',
            ]);
        }
    }

    /**
     * delete EventoPeriodo
     * @return EventoPeriodo
     */
    public function destroy(DeleteEventoPeriodo $request, $slug){
        if ($this->repository->delete($slug)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Evento eliminado exitosamente',
            ]);
        } else return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error eliminando el Evento'
        ]);
    }

}
