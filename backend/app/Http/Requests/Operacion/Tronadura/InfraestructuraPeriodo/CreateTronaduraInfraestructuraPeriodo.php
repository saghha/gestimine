<?php

namespace App\Http\Requests\Operacion\Tronadura\InfraestructuraPeriodo;

use App\Models\Operacion\TronaduraInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateTronaduraInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return TronaduraInfraestructuraPeriodo::$filters;
    }

    public function rules()
    {
        return TronaduraInfraestructuraPeriodo::$rules;
    }
}
