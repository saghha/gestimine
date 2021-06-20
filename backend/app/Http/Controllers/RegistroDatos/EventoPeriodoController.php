<?php

namespace App\Http\Controllers\RegistroDatos;

use Illuminate\Http\Request;
use App\Models\RegistroDatos\EventoPeriodo;
use App\Models\Cronograma\CronogramaInfraestructuraPeriodo;
use App\Models\Cronograma\CronogramaPreparacionPeriodo;
use App\Models\Cronograma\CronogramaProduccionPeriodo;
use App\Models\DatosMina\DatosMina;
use App\Repositories\RegistroDatos\EventoPeriodoRepository;
use App\Repositories\Cronograma\CronogramaInfraestructuraPeriodoRepository;
use App\Repositories\Cronograma\CronogramaPreparacionPeriodoRepository;
use App\Repositories\Cronograma\CronogramaProduccionPeriodoRepository;
use App\Repositories\DatosMina\DatosMinaRepository;
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
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        return $this->repository->queryAll()->with(['user'])->orderBy('created_at')->paginate(20);
        // $list = collect([]);
        // foreach ($data as $value) {
        //     $fecha_ev = new Carbon($value->fecha);
        //     $emisor = 
        //     $list->push([
        //         'evento' => $value->evento,
        //         'tipo_evento' => $value->tipo,
        //         'resultado' => $value->resultado,
        //         'mensaje' => $value->mensaje,
        //         'fecha' => $fecha_ev->format('d/m/Y'),
        //         'emisor' => $value->user->name,
        //     ]);
        // }
        // return $list->paginate(20);
    }

    /**
     * create EventoPeriodo
     * @return EventoPeriodo
     */
    public function store(CreateEventoPeriodo $request){
        $data = $request->validated();
        $for = User::where('email','!=',null)->get()->pluck('email')->toArray();
        try{
            $model = $this->repository->create(Arr::only(array_merge($data, ['id_usuario' => $request->user()->id]), $this->repository->attributes()));
            $array = [
                'name' => $request->user()->name,
            ];
            $subject = "Notificacion de evento";
            // Mail::mailer('smtp')->send('mails.emergency_call',array_merge($array,$request->all()), function($msj) use($subject,$for){
            //     $msj->from("mantos.cerro.verde.minera@gmail.com", "Emision Automatica de emergencia");
            //     $msj->subject($subject);
            //     $msj->to($for);
            // });
            $data_mail = array_merge($array,$request->all());
            Mail::to($for)->send(new EmergencyCallReceived($data_mail));
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Correo Enviado Exitosamente',
        ]);
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

    /**
     * get paged index of ShowEventoPeriodo
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function items(ShowEventoPeriodo $request){
        $data =  DatosMina::whereNull('deleted_at')->latest()->first();
        $infraestructura = CronogramaInfraestructuraPeriodo::where('id_datos_mina',$data->id)->select('nombre_infraestructura AS nombre')->get();
        $preparacion = CronogramaPreparacionPeriodo::where('id_datos_mina',$data->id)->select('nombre_infraestructura AS nombre')->get();
        $produccion = CronogramaProduccionPeriodo::where('id_datos_mina',$data->id)->select('nombre_produccion AS nombre')->get();
        return [
            'infraestructura' => $infraestructura,
            'preparacion' => $preparacion,
            'produccion' => $produccion,
            'periodo' => 1
        ];
    }

}
