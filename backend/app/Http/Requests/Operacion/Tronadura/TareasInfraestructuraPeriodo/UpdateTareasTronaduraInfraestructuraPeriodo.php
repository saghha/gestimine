<?php

namespace App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo;

use App\Models\Operacion\TareasTronaduraInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class UpdateTareasTronaduraInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return [
            //'id_tronadura' => 'decode_slug:App\Models\Operacion\TronaduraInfraestructuraPeriodo',
        ];
    }

    public function rules()
    {
        return [
            'id_tronadura'  => 'required|exists:App\Models\Operacion\TronaduraInfraestructuraPeriodo,id',
            'porcentaje_avance' => 'required',
        ];
    }
}