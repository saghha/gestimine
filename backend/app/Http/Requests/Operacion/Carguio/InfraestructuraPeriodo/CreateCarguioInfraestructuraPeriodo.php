<?php

namespace App\Http\Requests\Operacion\Carguio\InfraestructuraPeriodo;

use App\Models\Operacion\CarguioInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateCarguioInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return CarguioInfraestructuraPeriodo::$filters;
    }

    public function rules()
    {
        return CarguioInfraestructuraPeriodo::$rules;
    }
}
