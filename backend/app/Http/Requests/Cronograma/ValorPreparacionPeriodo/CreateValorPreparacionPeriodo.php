<?php

namespace App\Http\Requests\Cronograma\ValorPreparacionPeriodo;

use App\Models\Cronograma\ValorPreparacionPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateValorPreparacionPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return ValorPreparacionPeriodo::$filters;
    }

    public function rules()
    {
        return ValorPreparacionPeriodo::$rules;
    }
}