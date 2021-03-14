<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class ConvertApiFormData extends TransformsRequest
{
    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (is_string($value)) {
            switch ($value) {
                case 'true':
                    return true;
                case 'false':
                    return false;
                case 'null':
                case 'undefined':
                    return null;
            }
        }
        return $value;
    }
}
