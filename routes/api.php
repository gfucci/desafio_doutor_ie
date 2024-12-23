<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/v1/auth/register', [AuthController::class, 'register']);
Route::post('/v1/auth/token', [AuthController::class, 'token']);