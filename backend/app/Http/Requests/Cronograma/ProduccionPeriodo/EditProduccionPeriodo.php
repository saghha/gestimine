<?php

namespace App\Http\Requests\Cronograma\ProduccionPeriodo;

use App\Models\Cronograma\CronogramaProduccionPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class EditProduccionPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return array_merge(CronogramaProduccionPeriodo::$filters, [
            //DETALLES DE VALORES POR PERIODO//
            'valores.*.periodo' => 'cast:integer',
            'valores.*.ano' => 'cast:integer',
        ]);
    }

    public function rules()
    {
        return array_merge(CronogramaProduccionPeriodo::$rules, [
            //DETALLES DE VALORES POR PERIODO//
            'valores.*.periodo' => 'nullable',
            'valores.*.ano' => 'nullable',
            'valores.*.valor_desgloce' => 'nullable',
            'valores.*.valor_desgloce_perforacion' => 'nullable',
            'valores.*.valor_desgloce_carguio' => 'nullable',
            'valores.*.valor_desgloce_tronadura' => 'nullable',
            'valores.*.valor_desgloce_transporte' => 'nullable',
        ]);
    }
}