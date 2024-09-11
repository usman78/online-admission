<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admission;

Route::get('/register',[AuthController::class,'registerLoad']);
Route::post('/register', [AuthController::class, 'register'])->name('user.register');
Route::get('/confirm-email', [AuthController::class, 'showConfirmationForm'])->name('confirmation.form');
Route::post('/confirm-email', [AuthController::class, 'confirmEmail'])->name('confirmation.submit');
Route::get('/',[Admission::class,'admissionFormLoad']);
Route::post('/submit-admission-form',[Admission::class,'store'])->name('submitAdmissionForm');
Route::get('/login', [AuthController::class, 'loginLoad'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::post('/password/email/', [AuthController::class, 'sendResetToken'])->name('password.email');
Route::get('/password/email/', [AuthController::class, 'showforgetPasswordForm']);
Route::get('/password/reset/', [AuthController::class, 'showPasswordResetForm']);
Route::post('/password/reset/', [AuthController::class, 'resetPassword'])->name('password.update');

