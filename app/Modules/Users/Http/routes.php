<?php

use Illuminate\Support\Facades\Route;
use Users\Http\Controllers\AuthController;

Route::prefix("api")->group(function () {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get("/user", function () {
            return auth()->user();
        });
    });
});
