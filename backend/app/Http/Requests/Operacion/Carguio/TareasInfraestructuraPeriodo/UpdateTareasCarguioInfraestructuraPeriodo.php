<?php

namespace App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo;

use App\Models\Operacion\TareasCarguioInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class UpdateTareasCarguioInfraestructuraPeriodo extends FormRequest
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
            //'id_carguio' => 'decode_slug:App\Models\Operacion\CarguioInfraestructuraPeriodo',
        ];
    }

    public function rules()
    {
        return [
            'id_carguio'  => 'required|exists:App\Models\Operacion\CarguioInfraestructuraPeriodo,id',
            'porcentaje_avance' => 'required',
        ];
    }
}