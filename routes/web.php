<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/user-login',  [AuthController::class, 'userLogin']);


//for super admins
Route::name('super_admin.')->prefix('super_admin')->group(function() {
    Route::get('/',  [SuperAdminController::class, 'index']);
    Route::post('/add-school',  [SuperAdminController::class, 'addSchool']);

});