<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\QuizController;

Route::redirect('/', '/courses');

// Khách vãng lai: Đăng ký, đăng nhập (HT-03)
Route::get('/register', fn () => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Danh sách khóa học công khai (HT-01)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

// Phân hệ Học viên (student) - HT-03, HT-06
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', fn () => 'Xin chào! Đây là trang học viên.');
    Route::post('/courses/{course}/quiz/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/{attempt}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
});

// Phân hệ Giảng viên (teacher)
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', fn () => 'Xin chào! Đây là trang giảng viên.');
});

// Phân hệ Quản trị (admin) - HT-14
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn () => 'Xin chào! Đây là trang quản trị viên.');
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::patch('/admin/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('admin.users.toggle-status');
});