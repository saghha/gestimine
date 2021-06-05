<?php

namespace App\Filters;

use Waavi\Sanitizer\Contracts\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DecodeSlugFilter implements Filter
{
    public function apply($value, $options = [])
        {
            if (! Str::contains($options[0], '\\') || ! class_exists($options[0])) {
                return null;
            }
            $model = new $options[0];

            if ($model instanceof Model) {
                return $model->decodeSlug($value);
            }
            else return null;
        }
}