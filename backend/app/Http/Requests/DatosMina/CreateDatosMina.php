<?php

namespace App\Http\Requests\DatosMina;

use App\Models\DatosMina\DatosMina;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateDatosMina extends FormRequest
{
    use SanitizesInput;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function filters()
    {
        return DatosMina::$filters;
    }

    public function rules()
    {
        return DatosMina::$rules;
    }
}
