<?php

namespace App\Http\Requests\Cronograma\InfraestructuraPeriodo;

use App\Models\Cronograma\CronogramaInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class EditInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return array_merge(CronogramaInfraestructuraPeriodo::$filters, [
            //DETALLES DE VALORES POR PERIODO//
            'valores.*.periodo' => 'cast:integer',
            'valores.*.ano' => 'cast:integer',
        ]);
    }

    public function rules()
    {
        return array_merge(CronogramaInfraestructuraPeriodo::$rules, [
            //DETALLES DE VALORES POR PERIODO//
            'valores.*.periodo' => 'nullable',
            'valores.*.ano' => 'nullable',
            'valores.*.valor_desgloce' => 'nullable',
        ]);
    }
}
