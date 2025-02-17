<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\ChatController;
use App\Http\Controllers\Mobile\MobileAttendanceController;
use App\Http\Controllers\Mobile\MobileAttendanceReportController;
use App\Http\Controllers\Mobile\MobileReportController;
use App\Http\Controllers\Mobile\ParentMobileController;
use App\Http\Controllers\Mobile\ProfileController;
use App\Http\Controllers\Mobile\StaffController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);
Route::middleware('auth:sanctum')->get('/profile/{user_id}', [ProfileController::class, 'getProfile']);
Route::middleware('auth:sanctum')->post('/profile/picture', [ProfileController::class, 'updateProfilePicture']);
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/students', [MessageController::class, 'getSpecificStudent'])->name('students.getSpecific');


    Route::get('/attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

    Route::apiResource('messages', MessageController::class);
    Route::apiResource('chat', ChatController::class);

    //get student schedule attendances
    Route::get('/mobile/attendance/{studentScheduleId}', [MobileAttendanceController::class, 'show'])->name('mobile.attendance.show');

    // Add the route for getChildren function
    Route::get('/parents/{id}/children', [ParentMobileController::class, 'getChildren'])->name('parents.getChildren');
    Route::get('/students/{studentId}/schedule', [ParentMobileController::class, 'getStudentSchedule'])->name('students.getSchedule');

    Route::get('/staff/{branch_id}', [StaffController::class, 'index'])->name('staff.mobile.index');
    Route::get('/staff/schedule/{user_id}', [StaffController::class, 'showSchedule'])->name('staff.mobile.showSchedule');
    Route::get('/staff/attendance/{schedule_id}', [StaffController::class, 'showAttendance'])->name('staff.mobile.showAttendance');

    Route::get('/students/{studentId}/report', [MobileReportController::class, 'showPdf'])->name('students.report.showPdf');
    Route::get('/students/{reportId}/pdf', [MobileReportController::class, 'renderPdf']);

    Route::get('/attendance-reports/{id}', [MobileAttendanceReportController::class, 'show'])->name('mobile.attendanceReport.show');
});
