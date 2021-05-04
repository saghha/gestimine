<?php

namespace App\Http\Requests\Cronograma\ValorInfraestructuraPeriodo;

use App\Models\Cronograma\ValorInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class EditValorInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return ValorInfraestructuraPeriodo::$filters;
    }

    public function rules()
    {
        return ValorInfraestructuraPeriodo::$rules;
    }
}
