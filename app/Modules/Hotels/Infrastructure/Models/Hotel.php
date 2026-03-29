<?php

namespace Hotels\Infrastructure\Models;

use App\Casts\TimestampDefaultFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hotels\Domain\Enums\HotelStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RoomTypes\Infrastructure\Models\RoomType;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Builder;

class Hotel extends Model
{
    use HasFactory, HasFilter;

    protected array $filterSearchCols = ['name', 'address'];
    protected array $filterCols = ['city', 'status'];
    protected array $filterSort = ['name', 'rating', 'status', 'created_at'];
    protected int $filterLimit = 100;

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
