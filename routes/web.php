<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelloController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// TEST
Route::get("/hellworld", [HelloController::class, "hello"]);

