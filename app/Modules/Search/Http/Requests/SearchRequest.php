<?php

namespace Search\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "city"         => "required|string|max:255",
            "check_in"     => "required|date|after_or_equal:today",
            "check_out"    => "required|date|after:check_in",
            "adults_count" => "required|integer|min:1",
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new Exception($validator->errors()->first(), 403);
    }
}
