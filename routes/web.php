<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KindeUserController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KindeOrgUserTestController;
use App\Http\Controllers\KindeOrgPermissionReproController;

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

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    // ->middleware('throttle:6,1')
    ->name('password.email');

// Quick test routes
Route::post('/users/{userId}/reset-password', function (string $userId, App\Services\KindeUserManagementService $svc) {
    $svc->requestPasswordReset($userId);
    return 'OK';
});


Route::get('/tools/reset-password', [PasswordResetController::class, 'showForm'])->name('tools.reset.form');
Route::post('/tools/reset-password/set', [PasswordResetController::class, 'setPassword'])->name('tools.reset.set');
Route::post('/tools/reset-password/request', [PasswordResetController::class, 'requestReset'])->name('tools.reset.request');
Route::post('/tools/reset-password/set-permanent', [PasswordResetController::class, 'setPermanentPassword'])
    ->name('tools.reset.set_permanent');




Route::get('/kinde/users/form', [KindeUserController::class, 'form']);
Route::post('/kinde/users/add', [KindeUserController::class, 'addUsers'])->name('kinde.users.add');

Route::get('/kinde/test/form', [KindeOrgUserTestController::class, 'form']);
Route::post('/kinde/test/run', [KindeOrgUserTestController::class, 'run'])->name('kinde.test.run');

Route::get('/kinde/perm/form', [KindeOrgPermissionReproController::class, 'form'])->name('kinde.perm.form');
Route::post('/kinde/perm/run',  [KindeOrgPermissionReproController::class, 'run'])->name('kinde.perm.run');