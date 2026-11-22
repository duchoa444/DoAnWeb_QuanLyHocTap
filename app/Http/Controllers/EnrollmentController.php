<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
        try {
            $this->enrollmentService->enroll(Auth::user(), $course);

            return redirect()
                ->route('student.my-courses')
                ->with('success', 'Ghi danh khóa học thành công!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function myCourses(): View
    {
        $courses = $this->enrollmentService->getEnrolledCourses(Auth::user());
        $viewName = view()->exists('student.my-courses') ? 'student.my-courses' : 'courses.my-courses';

        return view($viewName, compact('courses'));
    }
}