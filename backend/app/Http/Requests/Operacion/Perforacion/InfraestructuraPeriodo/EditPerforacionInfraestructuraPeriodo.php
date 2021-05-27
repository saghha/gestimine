<?php

namespace App\Http\Requests\Operacion\Perforacion\InfraestructuraPeriodo;

use App\Models\Operacion\PerforacionInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class EditPerforacionInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return PerforacionInfraestructuraPeriodo::$filters;
    }

    public function rules()
    {
        return PerforacionInfraestructuraPeriodo::$rules;
    }
}
