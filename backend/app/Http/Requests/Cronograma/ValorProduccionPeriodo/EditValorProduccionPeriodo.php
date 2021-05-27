<?php

namespace App\Http\Requests\Cronograma\ValorProduccionPeriodo;

use App\Models\Cronograma\ValorProduccionPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class EditValorProduccionPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return ValorProduccionPeriodo::$filters;
    }

    public function rules()
    {
        return ValorProduccionPeriodo::$rules;
    }
}