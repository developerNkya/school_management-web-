<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolAdminController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/user-login',  [AuthController::class, 'userLogin']);


//for super admins
Route::name('super_admin')->prefix('super_admin')->group(function() {
    Route::get('/',  [SuperAdminController::class, 'index']);
    Route::post('/add-school',  [SuperAdminController::class, 'addSchool']);
    Route::get('/schools',  [SuperAdminController::class, 'schoolPage']);
    Route::get('/roles',  [SuperAdminController::class, 'rolesPage']);

});

Route::name('school_admin')->prefix('school_admin')->group(function() {
    Route::get('/',  [SchoolAdminController::class, 'index']);

    Route::post('/add-class',  [SchoolAdminController::class, 'addClass']);
});

Route::get('school_admin/classes',  [SchoolAdminController::class, 'classPage'])->name('add_class_page');

