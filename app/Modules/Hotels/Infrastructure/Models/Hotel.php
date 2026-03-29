<?php

namespace Hotels\Infrastructure\Models;

use App\Casts\TimestampDefaultFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hotels\Domain\Enums\HotelStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RoomTypes\Infrastructure\Models\RoomType;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "city",
        "address",
        "rating",
        "status",
    ];

    protected function casts(): array
    {
        return [
            "status"     => HotelStatus::class,
            "rating"     => "integer",
            "created_at" => TimestampDefaultFormat::class,
            "updated_at" => TimestampDefaultFormat::class,
        ];
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }
}
