<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KFA\KfaController;
use App\Http\Controllers\TokenAccessContorller;
use App\Http\Controllers\RawatInap\MedicationController;



Route::get('/', [TokenAccessContorller::class, 'getToken']);
Route::get('/kfa', [KfaController::class, 'getAllProductPaginateTest']);
Route::get('/kfa-detail', [KfaController::class, 'getDetailProductPaginateTest']);
Route::get('/kfa-test', [KfaController::class, 'getDetailProductPaginateTestAplication']);
