<?php

namespace RoomTypes\Infrastructure\Models;

use App\Casts\TimestampDefaultFormat;
use Hotels\Infrastructure\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RoomTypes\Domain\Enums\RoomTypeName;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Builder;

class RoomType extends Model
{
    use HasFactory, HasFilter;

    protected array $filterCols = ['hotel_id', 'status'];
    protected array $filterSearchCols = ['name'];
    protected array $filterSort = ['base_price', 'total_rooms', 'max_occupancy', 'created_at'];
    protected int $filterLimit = 100;

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

    public function scopeTableFilter(Builder $query, $request): Builder
    {
        $this->applyLimit($query, $request);
        $this->applySort($query, $request);
        $this->applySearch($query, $request);
        $this->applyColumnFilters($query, $request);
        $this->applyCustomFilters($query, $request);
        return $query;
    }
}
