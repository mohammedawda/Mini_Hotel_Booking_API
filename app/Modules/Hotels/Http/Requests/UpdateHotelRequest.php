<?php

namespace Hotels\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Hotels\Domain\Enums\HotelStatus;

class UpdateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"    => "sometimes|string|max:255",
            "city"    => "sometimes|string|max:255",
            "address" => "sometimes|string",
            "rating"  => "sometimes|integer|min:1|max:5",
            "status"  => ["sometimes", Rule::enum(HotelStatus::class)],
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new Exception($validator->errors()->first(), 403);
    }
}
