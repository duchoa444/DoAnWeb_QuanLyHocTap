<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\StudentProfileController;

Route::redirect('/', '/courses');

// Khách vãng lai: Đăng ký, đăng nhập (HT-03)
Route::get('/register', fn () => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Danh sách khóa học công khai (HT-01)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
// Chi tiết khóa học công khai (HT-02)
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Phân hệ Học viên (student) - HT-03, HT-06, HT-07
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', fn () => 'Xin chào! Đây là trang học viên.');
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('student.my-courses');
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{course}/quiz/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/{attempt}/submit', [QuizController::class, 'submit'])->name('quiz.submit');

    // Hồ sơ cá nhân & Lịch sử thi (HT-07)
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('student.profile');
    Route::put('/profile', [StudentProfileController::class, 'updateProfile'])->name('student.profile.update');
    Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('student.password.update');
    Route::get('/exam-history', [StudentProfileController::class, 'index'])->name('student.exam-history');
});

// Phân hệ Giảng viên (teacher)
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', function () {
        return 'Xin chào! Đây là trang giảng viên.';
    });
});

// Phân hệ Quản trị viên (admin) - HT-14, HT-15
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