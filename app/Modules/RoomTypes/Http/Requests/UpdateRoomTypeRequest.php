<?php

namespace RoomTypes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use RoomTypes\Domain\Enums\RoomTypeName;

class UpdateRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "hotel_id"       => "sometimes|exists:hotels,id",
            "name"           => ["sometimes", Rule::enum(RoomTypeName::class)],
            "max_occupancy"  => "sometimes|integer|min:1",
            "base_price"     => "sometimes|numeric|min:0",
            "total_rooms"    => "sometimes|integer|min:0",
        ];
    }
}
