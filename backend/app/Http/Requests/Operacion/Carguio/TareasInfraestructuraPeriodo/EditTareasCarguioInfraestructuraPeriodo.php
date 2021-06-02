<?php

namespace App\Http\Requests\Operacion\Carguio\TareasInfraestructuraPeriodo;

use App\Models\Operacion\TareasCarguioInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class EditTareasCarguioInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return TareasCarguioInfraestructuraPeriodo::$filters;
    }

    public function rules()
    {
        return TareasCarguioInfraestructuraPeriodo::$rules;
    }
}