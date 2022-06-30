<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;

Route::get('/users', [UserController::class, 'index']);
Route::get('/teachers', [TeacherController::class, 'index']);
