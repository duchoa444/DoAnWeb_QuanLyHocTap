
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
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

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return 'Xin chào! Đây là trang quản trị viên.';
    });

    Route::get('/admin/users', [UserManagementController::class, 'index'])
        ->name('admin.users.index');
    Route::post('/admin/users', [UserManagementController::class, 'store'])
        ->name('admin.users.store');
    Route::patch('/admin/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
        ->name('admin.users.toggle-status');
});
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    