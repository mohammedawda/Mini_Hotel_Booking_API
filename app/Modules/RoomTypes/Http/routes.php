<?php

use Illuminate\Support\Facades\Route;
use RoomTypes\Http\Controllers\RoomTypesController;

Route::prefix("api/room-types")->group(function () {
    Route::get("/", [RoomTypesController::class, "index"]);
    Route::get("/{id}", [RoomTypesController::class, "show"]);

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/", [RoomTypesController::class, "store"]);
        Route::put("/{id}", [RoomTypesController::class, "update"]);
        Route::delete("/{id}", [RoomTypesController::class, "destroy"]);
    });
});
