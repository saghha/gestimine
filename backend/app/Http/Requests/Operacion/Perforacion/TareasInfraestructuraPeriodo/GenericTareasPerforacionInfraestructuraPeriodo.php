<?php

namespace App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo;

use App\Models\Operacion\TareasPerforacionInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class GenericTareasPerforacionInfraestructuraPeriodo extends FormRequest
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
            //'id_perforacion' => 'decode_slug:App\Models\Operacion\PerforacionInfraestructuraPeriodo',
            'periodo' => 'digit|cast:integer',
            'ano' => 'digit|cast:integer',
        ];
    }

    public function rules()
    {
        return [
            'id_perforacion'  => 'required|exists:App\Models\Operacion\PerforacionInfraestructuraPeriodo,id',
            'periodo' => 'nullable',
            'ano' => 'nullable',
        ];
    }
}