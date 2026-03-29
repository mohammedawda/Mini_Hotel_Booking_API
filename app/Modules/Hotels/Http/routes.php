<?php

use Illuminate\Support\Facades\Route;
use Hotels\Http\Controllers\HotelsController;

Route::prefix("api/hotels")->group(function () {
    Route::get("/", [HotelsController::class, "index"]);
    Route::get("/{id}", [HotelsController::class, "show"]);

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/", [HotelsController::class, "store"]);
        Route::put("/{id}", [HotelsController::class, "update"]);
        Route::delete("/{id}", [HotelsController::class, "destroy"]);
    });
});
