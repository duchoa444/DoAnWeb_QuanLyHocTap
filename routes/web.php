<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserManagementController;

Route::redirect('/', '/courses');

// Hiển thị trang đăng ký
Route::get('/register', function () {
    return view('auth.register');
});

// Xử lý đăng ký
Route::post('/register', [AuthController::class, 'register']);

// Hiển thị trang đăng nhập
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Xử lý đăng nhập
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth');

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return 'Xin chào! Đây là trang học viên.';
    });
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', function () {
        return 'Xin chào! Đây là trang giảng viên.';
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return 'Xin chào! Đây là trang quản trị viên.';
    })->name('dashboard');

    // Quản lý tài khoản (HT-14)
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

    // Duyệt và ẩn khóa học vi phạm (HT-15 - gộp chung trang admin/users)
    Route::get('/courses', [UserManagementController::class, 'index'])->name('courses.index');
    Route::patch('/courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
    Route::patch('/courses/{course}/hide', [AdminCourseController::class, 'hide'])->name('courses.hide');

    // CRUD Danh mục (HT-15)
    Route::resource('categories', AdminCategoryController::class)->only(['index', 'store', 'update', 'destroy']);
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');