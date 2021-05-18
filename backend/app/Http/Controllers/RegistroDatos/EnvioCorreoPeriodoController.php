<?php

namespace App\Http\Controllers\RegistroDatos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Validator;
use \Carbon\Carbon;
use RuntimeException;
use Mail;
use App\Mail\EmergencyCallReceived;

class EnvioCorreoPeriodoController extends Controller
{
    //Se incluye el repositorio de EventoPeriodo
    /** @var EnvioCorreoPeriodo */
    private $distressCall;

    public function __construct(EmergencyCallReceived $distressCall){
        $this->distressCall = $distressCall;
    }

    public function contact(Request $request){
        $subject = "Asunto del correo";
        $for = "correo_que_recibira_el_mensaje@gmail.com";
        Mail::send('email',$request->all(), function($msj) use($subject,$for){
            $msj->from("tucorreo@gmail.com","NombreQueApareceráComoEmisor");
            $msj->subject($subject);
            $msj->to($for);
        });
        return redirect()->back();
    }

}
