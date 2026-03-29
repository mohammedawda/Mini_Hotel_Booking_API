<?php

namespace Bookings\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id"           => $this->id,
            "hotel_id"     => $this->hotel_id,
            "room_type_id" => $this->room_type_id,
            "guest_name"   => $this->guest_name,
            "guest_email"  => $this->guest_email,
            "check_in"     => $this->check_in->format('Y-m-d'),
            "check_out"    => $this->check_out->format('Y-m-d'),
            "rooms_count"  => $this->rooms_count,
            "adults_count" => $this->adults_count,
            "total_price"  => (float) $this->total_price,
            "status"       => $this->status,
            "created_at"   => $this->created_at,
        ];
    }
}
