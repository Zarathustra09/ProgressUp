<?php

use App\Http\Controllers\ParentController;
use App\Http\Controllers\ParentDetailsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomStudentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentMedicalInformationController;
use App\Http\Controllers\StudentSchoolDetailsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::put('/profile/upload-image', [ProfileController::class, 'uploadProfileImage'])->name('profile.uploadImage');
Route::delete('/profile/reset-image', [ProfileController::class, 'resetProfileImage'])->name('profile.resetImage');


Route::get('/parent/index', [ParentController::class, 'index'])->name('parent.index');
Route::post('/parent', [ParentController::class, 'store'])->name('parent.store');
Route::get('/parent/{user}', [ParentController::class, 'show'])->name('parent.show');
Route::put('/parent/{user}', [ParentController::class, 'update'])->name('parent.update');
Route::delete('/parent/{user}', [ParentController::class, 'destroy'])->name('parent.destroy');

Route::get('/parent-details/index/{id}', [ParentDetailsController::class, 'index'])->name('parent-details.index');
Route::get('/parent-student/index/{id}', [ParentDetailsController::class, 'indexStudent'])->name('parent-student.index');
Route::get('/parent-student/create/{id}', [ParentDetailsController::class, 'create'])->name('parent-student.create');
Route::post('/parent-student/store/{id}', [ParentDetailsController::class, 'store'])->name('parent-student.store');
Route::get('/parent-student/show/{id}/{studentId}', [ParentDetailsController::class, 'show'])->name('parent-student.show');
Route::put('/parent-student/update/{id}/{studentId}', [ParentDetailsController::class, 'update'])->name('parent-student.update');

Route::get('/student/index', [StudentController::class, 'index'])->name('student.index');

Route::get('/branch/index', [RoomController::class, 'index'])->name('room.index');
Route::post('/branch', [RoomController::class, 'store'])->name('room.store');
Route::get('/branch/{id}', [RoomController::class, 'show'])->name('room.single.show');
Route::put('/branch/{id}', [RoomController::class, 'update'])->name('room.update');
Route::delete('/branch/{id}', [RoomController::class, 'destroy'])->name('room.destroy');
Route::get('/listBranch', [RoomController::class, 'list'])->name('branch.list');


Route::get('/staff/index', [StaffController::class, 'index'])->name('staff.index');
Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
Route::get('/staff/{user}', [StaffController::class, 'show'])->name('staff.show');
Route::put('/staff/{user}', [StaffController::class, 'update'])->name('staff.update');
Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');

Route::get('/rooms/{id}', [RoomStudentController::class, 'show'])->name('room.show');
Route::post('/room-student', [RoomStudentController::class, 'store'])->name('room-student.store');
Route::delete('/room-student/{id}', [RoomStudentController::class, 'destroy'])->name('room-student.destroy');

Route::get('/students/list', [StudentController::class, 'list'])->name('students.list');

Route::resource('student-medical-information', StudentMedicalInformationController::class);
Route::resource('student-school-details', StudentSchoolDetailsController::class);

