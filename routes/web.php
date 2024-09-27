<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusManagementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SchoolAdminController;
use App\Http\Controllers\StarterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\SuperAdminController;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Route;



Route::get('/', [StarterController::class, 'index']);
Route::get('/login', [StarterController::class, 'login'])->name('user_login');
Route::get('/contact-us', [StarterController::class, 'contactUs']);
Route::get('/about-us', [StarterController::class, 'aboutUs']);
Route::get('/downloads', [StarterController::class, 'Downloads']);
Route::post('/contact-message', [StarterController::class, 'contactMessage'])->name('contact.message');

Route::post('/user-login', [AuthController::class, 'userLogin']);
Route::get('/initial-user', [AuthController::class, 'initialUser']);


//for super admins
Route::group(['prefix' => 'super_admin'], function () {
    Route::get('/home', [SuperAdminController::class, 'index']);
    Route::post('/add-school', [SuperAdminController::class, 'addSchool']);
    Route::get('/schools', [SuperAdminController::class, 'schoolPage'])->name('add_school_page');
    Route::get('/roles', [SuperAdminController::class, 'rolesPage']);
    Route::get('/activation-page', [SuperAdminController::class, 'activationPage'])->name('activationPage');
    Route::get('/filter-users/{name}', [SuperAdminController::class, 'filterUsers'])->name('filter.users');
    Route::post('/alter-user-status/{id}', [SuperAdminController::class, 'alterUserStatus'])->name('alter.user.status');

});


//for school admins:::
Route::group(['prefix' => 'school_admin'], function () {
    Route::get('/home', [SchoolAdminController::class, 'index']);
    Route::get('/promote-class', [SchoolAdminController::class, 'promoteClass'])->name('promote_class');
    Route::post('/handle-promotion', [SchoolAdminController::class, 'handlePromotion']);
    Route::get('/view-suggestions', [SchoolAdminController::class, 'viewSuggestions']);
    Route::post('/add-class', [SchoolAdminController::class, 'addClass']);
    Route::get('/classes', [SchoolAdminController::class, 'classPage'])->name('add_class_page');
    Route::get('/teachers', [SchoolAdminController::class, 'teachersPage'])->name('all_teachers_page');
    Route::get('/students', [SchoolAdminController::class, 'studentsPage'])->name('add_student_page');
    Route::get('/examinations', [SchoolAdminController::class, 'examinationsPage'])->name('all_exam_page');
    Route::get('/all-subjects', [SchoolAdminController::class, 'subjectsPage'])->name('all_subjects_page');
    Route::post('/add-subject', [SchoolAdminController::class, 'addSubject']);
    Route::post('/add-marks', [ExamController::class, 'addMarks'])->name('marks.store');
    Route::get('/exam-details/{id}', [ExamController::class, 'examDetails']);
    Route::post('/add-exam', [ExamController::class, 'store']);
    Route::post('/edit-exam', [ExamController::class, 'editExam']);
    Route::post('/deleteSuggestion', [SuggestionController::class, 'deleteSuggestion'])->name('deleteSuggestion');
    Route::get('/marks', [ExamController::class, 'marks'])->name('marks.index');
    Route::get('/organize_events', [EventController::class, 'organizeEvent'])->name('organizeEvent');
    Route::post('/school_admin/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/tabulation', [ExamController::class, 'tabulation'])->name('admin.tabulation');
    Route::post('/add-student', [SchoolAdminController::class, 'addStudent'])->name('add_student');
    Route::get('/get-streams/{classId}', [SchoolAdminController::class, 'getStreams']);
    Route::get('/get-subjects/{classId}/{stream}', [SchoolAdminController::class, 'getSubjects']);
    Route::post('/add-teacher', [SchoolAdminController::class, 'addTeacher']);
})->middleware('auth');


// attendence
Route::group(['prefix' => 'attendence'], function () {
    Route::get('/create-new', [AttendenceController::class, 'createNew']);
    Route::post('/new-attendence', [AttendenceController::class, 'newAttendence'])->name('attendance.saveNewAttendence');
    Route::get('/add-attendence', [AttendenceController::class, 'showAddAttendenceForm'])->name('attendance.addAttendence');
    Route::post('/fetch-students', [AttendenceController::class, 'fetchStudents'])->name('attendance.fetchStudents');
    Route::post('/save-attendence', [AttendenceController::class, 'saveAttendence'])->name('attendance.storeData');

})->middleware('auth');


Route::group(['prefix' => 'student'], function () {
    Route::get('/home', [StudentController::class, 'index']);
    Route::get('/events', [StudentController::class, 'events']);
    Route::get('/attendence', [StudentController::class, 'attendence'])->name('student.attendence');
    Route::get('/about_me', [StudentController::class, 'aboutMe']);
    Route::get('/marks', [StudentController::class, 'marks'])->name('student.marks');
    Route::get('/view-marks', [StudentController::class, 'viewMarks'])->name('view-marks');
    Route::get('/schools', [SuperAdminController::class, 'schoolPage'])->name('add_school_page');
    Route::get('/suggestions', [StudentController::class, 'suggestionPage'])->name('suggestions');
    Route::post('/add-suggestion', [SuggestionController::class, 'addSuggestion']);
    Route::get('/fetchEvents', [EventController::class, 'organizeEvent'])->name('organizeEvent');
    Route::get('/view-assignments', [StudentController::class, 'viewAssignments'])->name('viewAssignments');
});


Route::group(['prefix' => 'helper'], function () {
    Route::get('/class-existence-checker', [HelperController::class, 'classExistance']);
    Route::post('/delete-item', [HelperController::class, 'deleteById'])->name('deleteById');
});

Route::group(['prefix' => 'assignment'], function () {
    Route::get('/add-assignment', [AssignmentController::class, 'addAssignment']);
    Route::post('/save-assignment', [AssignmentController::class, 'saveAssignment'])->name('saveAssignment');
    Route::post('/download-assignment', [AssignmentController::class, 'downloadFile'])->name('downloadFile');
});


Route::group(['prefix' => 'bus-management'], function () {
    Route::get('/all-drivers', [BusManagementController::class, 'allDrivers'])->name('allDriversPage');
    Route::post('/add-driver', [BusManagementController::class, 'addDriver']);
    Route::post('/daily-bus-attendance', [BusManagementController::class, 'dailyBusAttendance']);
    Route::get('/driver-attendance', [BusManagementController::class, 'driverAttendance']);
})->middleware('auth');


Route::group(['prefix' => 'results'], function () {
    Route::get('/unsent-exams', [ResultController::class, 'unsentExams'])->name('unsentExams');
    Route::get('/sent-results', [ResultController::class, 'sendResultsPage'])->name('sendResultsPage');
    Route::post('/send-exams', [ResultController::class, 'sendExams'])->name('sendExams');
})->middleware('auth');