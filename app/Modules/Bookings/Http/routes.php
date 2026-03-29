<?php

use Illuminate\Support\Facades\Route;
use Bookings\Http\Controllers\BookingsController;

Route::prefix("api/bookings")->middleware("auth:sanctum")->group(function () {
    Route::post("/", [BookingsController::class, "store"]);
    Route::get("/{id}", [BookingsController::class, "show"]);
    Route::delete("/{id}", [BookingsController::class, "destroy"]);
});
