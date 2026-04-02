<?php

namespace Users\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            "email"    => "required|string|email",
            "password" => "required|string",
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new Exception($validator->errors()->first(), 403);
    }
}
