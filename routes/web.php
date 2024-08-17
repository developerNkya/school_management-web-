<?php

use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\SchoolAdminController;
use App\Http\Controllers\StudentController;
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
    Route::get('/tabulation', [ExamController::class, 'tabulation'])->name('admin.tabulation');
    Route::post('/add-student',  [SchoolAdminController::class, 'addStudent'])->name('add_student');
    Route::get('/get-streams/{classId}', [SchoolAdminController::class, 'getStreams']);
    Route::get('/get-subjects/{classId}/{stream}', [SchoolAdminController::class, 'getSubjects']);
    Route::post('/add-teacher', [SchoolAdminController::class, 'addTeacher']);
})->middleware('auth');


// attendence
Route::group(['prefix' => 'attendence'], function () {
    Route::get('/create-new',  [AttendenceController::class, 'createNew']);
    Route::post('/new-attendence',  [AttendenceController::class, 'newAttendence'])->name('attendance.createNew');
    // Route::get('/add-attendence',  [AttendenceController::class, 'addAttendencePage'])->name('attendance.addAttendence');
    // Route::post('/attendence',  [AttendenceController::class, 'addAttendencePage'])->name('attendance.addAttendencePage');
    // Route::post('/save-attendence',  [AttendenceController::class, 'saveAttendence'])->name('attendance.storeData');
    // Route::post('/add-class',  [SchoolAdminController::class, 'addClass']);
    Route::get('/add-attendence', [AttendenceController::class, 'showAddAttendenceForm'])->name('attendance.addAttendence');
Route::post('/add-attendence', [AttendenceController::class, 'fetchStudents'])->name('attendance.fetchStudents');
Route::post('/save-attendence', [AttendenceController::class, 'saveAttendence'])->name('attendance.storeData');

})->middleware('auth');


Route::group(['prefix' => 'student'], function () {
    Route::get('/',  [StudentController::class, 'index']);
    Route::get('/about_me',  [StudentController::class, 'aboutMe']);
    Route::get('/marks',  [StudentController::class, 'marks'])->name('student.marks');
    Route::get('/view-marks', [StudentController::class, 'viewMarks'])->name('view-marks');
    Route::get('/schools',  [SuperAdminController::class, 'schoolPage'])->name('add_school_page');
    Route::get('/roles',  [SuperAdminController::class, 'rolesPage']);
});
