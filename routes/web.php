<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KFA\KfaController;
use App\Http\Controllers\TokenAccessContorller;

Route::get('/', [TokenAccessContorller::class, 'getToken']);
Route::get('/kfa', [KfaController::class, 'getAllProductPaginateTest']);
