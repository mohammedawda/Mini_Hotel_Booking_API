<?php

namespace Bookings\Infrastructure\Models;

use App\Casts\TimestampDefaultFormat;
use Bookings\Domain\Enums\BookingStatus;
use Hotels\Infrastructure\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RoomTypes\Infrastructure\Models\RoomType;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory, HasFilter;

    protected array $filterCols = ['hotel_id', 'room_type_id', 'status'];
    protected array $filterSearchCols = ['guest_name', 'guest_email'];
    protected array $filterDates = ['check_in_from' => 'check_in', 'check_in_to' => 'check_in', 'check_out_from' => 'check_out', 'check_out_to' => 'check_out'];
    protected array $filterSort = ['check_in', 'check_out', 'total_price', 'created_at'];
    protected int $filterLimit = 100;

    protected $fillable = [
        "hotel_id",
        "room_type_id",
        "guest_name",
        "guest_email",
        "check_in",
        "check_out",
        "rooms_count",
        "adults_count",
        "total_price",
        "status",
    ];

    protected function casts(): array
    {
        return [
            "status"      => BookingStatus::class,
            "check_in"    => "date",
            "check_out"   => "date",
            "total_price" => "decimal:2",
            "created_at"  => TimestampDefaultFormat::class,
            "updated_at"  => TimestampDefaultFormat::class,
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\BookingFactory::new();
    }
}
