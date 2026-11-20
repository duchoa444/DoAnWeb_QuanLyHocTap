<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserManagementController;

// Trang chủ
Route::redirect('/', '/courses');

// Đăng ký, đăng nhập, đăng xuất
Route::get('/register', fn () => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Danh sách & chi tiết khóa học công khai (HT-01, HT-02)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Học bài & đánh dấu hoàn thành (HT-05)
Route::middleware('auth')->group(function () {
    Route::get('/courses/{course}/learn/{lesson?}', [CourseController::class, 'learn'])->name('courses.learn');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [CourseController::class, 'completeLesson'])->name('lessons.complete');
});

// Phân hệ học viên (Student)
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', fn () => 'Xin chào! Đây là trang học viên.');

    // Ghi danh & Khóa học của tôi (HT-04, HT-05)
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('student.my-courses');
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');

    // Làm bài trắc nghiệm (HT-06)
    Route::get('/courses/{courseId}/quiz', [QuizController::class, 'show'])->name('student.quizzes.show');
    Route::match(['get', 'post'], '/courses/{courseId}/quiz/start', [QuizController::class, 'show'])->name('quiz.start');
    Route::post('/quiz/{attemptId}/submit', [QuizController::class, 'submit'])->name('student.quizzes.submit');
    Route::post('/quizzes/{attemptId}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{attemptId}/result', [QuizController::class, 'result'])->name('student.quizzes.result');
    Route::get('/quizzes/{attemptId}/result', [QuizController::class, 'result'])->name('quiz.result');

    // Hồ sơ cá nhân & Lịch sử thi (HT-07)
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('student.profile');
    Route::put('/profile', [StudentProfileController::class, 'updateProfile'])->name('student.profile.update');
    Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('student.password.update');
    Route::get('/exam-history', [StudentProfileController::class, 'index'])->name('student.exam-history');
});

// Phân hệ giảng viên (HT-09 & HT-11)
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return 'Xin chào! Đây là trang giảng viên.';
        })->name('dashboard');

        Route::resource('courses', TeacherCourseController::class)
            ->except(['show']);

        // Quản lý câu hỏi trắc nghiệm - HT-11
        Route::get('/courses/{course}/questions', [QuestionController::class, 'index'])
            ->name('questions.index');
        Route::patch('/courses/{course}/quiz-duration', [QuestionController::class, 'updateDuration'])
            ->name('questions.duration');
        Route::post('/courses/{course}/questions', [QuestionController::class, 'store'])
            ->name('questions.store');
        Route::put('/courses/{course}/questions/{question}', [QuestionController::class, 'update'])
            ->name('questions.update');
        Route::delete('/courses/{course}/questions/{question}', [QuestionController::class, 'destroy'])
            ->name('questions.destroy');
    });

// Phân hệ quản trị viên (Admin)
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return 'Xin chào! Đây là trang quản trị viên.';
        })->name('dashboard');

        // Quản lý tài khoản
        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])
            ->name('users.store');
        Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Duyệt và ẩn khóa học (HT-15)
        Route::get('/courses', [AdminCourseController::class, 'index'])
            ->name('courses.index');
        Route::get('/approve-courses', [AdminCourseController::class, 'index'])
            ->name('courses.approve-page');
        Route::patch('/courses/{course}/approve', [AdminCourseController::class, 'approve'])
            ->name('courses.approve');
        Route::patch('/courses/{course}/hide', [AdminCourseController::class, 'hide'])
            ->name('courses.hide');

        // Quản lý danh mục
        Route::resource('categories', AdminCategoryController::class)
            ->only(['index', 'store', 'update', 'destroy']);
    });