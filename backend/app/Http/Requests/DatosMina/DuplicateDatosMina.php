<?php

namespace App\Http\Requests\DatosMina;

use App\Models\DatosMina\DatosMina;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class DuplicateDatosMina extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'fecha_estado' => 'trim|format_date:d/m/Y, Y-m-d',
        ];
    }

    public function rules()
    {
        return [
            'fecha_estado' => 'nullable',
        ];
    }
}
