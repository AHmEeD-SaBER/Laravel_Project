<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'show'])->name('register');
Route::post('/', [UserController::class, 'register'])->name('register.submit');
Route::post('/validate-field', [UserController::class, 'validateField'])->name('validate.field');
