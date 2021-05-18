<?php

namespace App\Http\Requests\RegistroDatos\EventoPeriodo;

use App\Models\RegistroDatos\EventoPeriodo;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateEventoPeriodo extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return EventoPeriodo::$filters;
    }

    public function rules()
    {
        return EventoPeriodo::$rules;
    }
}
