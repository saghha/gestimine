<?php

namespace App\Http\Requests\Operacion\Tronadura\TareasInfraestructuraPeriodo;

use App\Models\Operacion\TareasTronaduraInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateTareasTronaduraInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return TareasTronaduraInfraestructuraPeriodo::$filters;
    }

    public function rules()
    {
        return TareasTronaduraInfraestructuraPeriodo::$rules;
    }
}