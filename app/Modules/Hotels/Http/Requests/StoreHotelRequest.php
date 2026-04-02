<?php

namespace Hotels\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Hotels\Domain\Enums\HotelStatus;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"    => "required|string|max:255",
            "city"    => "required|string|max:255",
            "address" => "required|string",
            "rating"  => "required|integer|min:1|max:5",
            "status"  => ["nullable", Rule::enum(HotelStatus::class)],
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new Exception($validator->errors()->first(), 403);
    }
}
