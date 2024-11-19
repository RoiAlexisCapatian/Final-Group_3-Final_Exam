<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('login');
});



Route::post('/login', [UserController::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard', [UserController::class, 'showDashboard']);
Route::get('/get-user/{userid}', [UserController::class, 'getUser'])->name('getUser');
Route::post('/update-user', [UserController::class, 'updateUser']);
// Route::post('/update-user', [UserController::class, 'updateUser']);


