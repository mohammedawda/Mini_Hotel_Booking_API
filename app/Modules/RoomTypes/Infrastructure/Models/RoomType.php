<?php

namespace RoomTypes\Infrastructure\Models;

use App\Casts\TimestampDefaultFormat;
use Hotels\Infrastructure\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RoomTypes\Domain\Enums\RoomTypeName;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        "hotel_id",
        "name",
        "max_occupancy",
        "base_price",
        "total_rooms",
    ];

    protected function casts(): array
    {
        return [
            "name"       => RoomTypeName::class,
            "base_price" => "decimal:2",
            "created_at" => TimestampDefaultFormat::class,
            "updated_at" => TimestampDefaultFormat::class,
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
