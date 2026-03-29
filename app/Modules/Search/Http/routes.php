<?php

use Illuminate\Support\Facades\Route;
use Search\Http\Controllers\SearchController;

Route::get("api/search", [SearchController::class, "index"]);
