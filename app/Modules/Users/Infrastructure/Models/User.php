<?php

namespace Users\Infrastructure\Models;

use App\Casts\TimestampDefaultFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "name",
        "email",
        "password",
    ];

    protected $hidden = [
        "password",
        "remember_token",
    ];

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password"          => "hashed",
            "created_at"        => TimestampDefaultFormat::class,
            "updated_at"        => TimestampDefaultFormat::class,
        ];
    }
}
