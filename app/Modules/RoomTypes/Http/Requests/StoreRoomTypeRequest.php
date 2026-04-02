<?php

namespace RoomTypes\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use RoomTypes\Domain\Enums\RoomTypeName;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "hotel_id"       => "required|exists:hotels,id",
            "name"           => ["required", Rule::enum(RoomTypeName::class)],
            "max_occupancy"  => "required|integer|min:1",
            "base_price"     => "required|numeric|min:0",
            "total_rooms"    => "required|integer|min:0",
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new Exception($validator->errors()->first(), 403);
    }
}
