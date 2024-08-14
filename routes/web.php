<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\SchoolAdminController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('user_login');;

Route::post('/user-login',  [AuthController::class, 'userLogin']);

Route::get('/initial-user',  [AuthController::class, 'initialUser']);


//for super admins
Route::group(['prefix' => 'super_admin'], function () {
    Route::get('/',  [SuperAdminController::class, 'index']);
    Route::post('/add-school',  [SuperAdminController::class, 'addSchool']);
    Route::get('/schools',  [SuperAdminController::class, 'schoolPage'])->name('add_school_page');
    Route::get('/roles',  [SuperAdminController::class, 'rolesPage']);
});


//for school admins:::
Route::group(['prefix' => 'school_admin'], function () {
    Route::get('/',  [SchoolAdminController::class, 'index']);
    Route::post('/add-class',  [SchoolAdminController::class, 'addClass']);
    Route::get('/classes',  [SchoolAdminController::class, 'classPage'])->name('add_class_page');
    Route::get('/teachers',  [SchoolAdminController::class, 'teachersPage'])->name('all_teachers_page');
    Route::get('/students',  [SchoolAdminController::class, 'studentsPage'])->name('add_student_page');
    Route::get('/examinations',  [SchoolAdminController::class, 'examinationsPage'])->name('all_exam_page');
    Route::get('/all-subjects',  [SchoolAdminController::class, 'subjectsPage'])->name('all_subjects_page');
    Route::post('/add-subject', [SchoolAdminController::class, 'addSubject']);
    Route::post('/add-marks', [ExamController::class, 'addMarks'])->name('marks.store');;
    Route::post('/add-exam', [ExamController::class, 'store']);
    Route::get('/marks', [ExamController::class, 'marks'])->name('marks.index');
    Route::post('/add-student',  [SchoolAdminController::class, 'addStudent'])->name('add_student');
    Route::get('/get-streams/{classId}', [SchoolAdminController::class, 'getStreams']);
    Route::get('/get-subjects/{classId}/{stream}', [SchoolAdminController::class, 'getSubjects']);
    Route::post('/add-teacher', [SchoolAdminController::class, 'addTeacher']);


})->middleware('auth');
