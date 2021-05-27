<?php

namespace App\Http\Requests\Operacion\Perforacion\TareasInfraestructuraPeriodo;

use App\Models\Operacion\TareasPerforacionInfraestructuraPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateTareasPerforacionInfraestructuraPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return TareasPerforacionInfraestructuraPeriodo::$filters;
    }

    public function rules()
    {
        return TareasPerforacionInfraestructuraPeriodo::$rules;
    }
}