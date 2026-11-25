<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Services\EnrollmentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    public function store(Course $course): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role !== 'student') {
            abort(403, 'Chỉ tài khoản Học viên mới có thể ghi danh khóa học.');
        }

        try {
            $this->enrollmentService->enroll($user, $course);

            return redirect()
                ->route('student.my-courses')
                ->with('success', 'Ghi danh khóa học thành công!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function myCourses(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $courses = $this->enrollmentService->getEnrolledCourses($user);
        $viewName = view()->exists('student.my-courses') ? 'student.my-courses' : 'courses.my-courses';

        return view($viewName, compact('courses'));
    }
}