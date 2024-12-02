<?php

use App\Http\Controllers\ParentController;
use App\Http\Controllers\ParentDetailsController;
use App\Http\Controllers\ProfileController;
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


Route::resource('student-medical-information', StudentMedicalInformationController::class);
Route::resource('student-school-details', StudentSchoolDetailsController::class);

