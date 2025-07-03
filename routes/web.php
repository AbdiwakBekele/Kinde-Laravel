<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KindeUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'redirectToKinde'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/callback', [AuthController::class, 'handleKindeCallback']);

Route::get('/dashboard', function () {
    return view('dashboard');
});


Route::view('/delete-kinde-user', 'delete-user'); // Show the form
Route::delete('/kinde/users/{userId}', [KindeUserController::class, 'delete']);