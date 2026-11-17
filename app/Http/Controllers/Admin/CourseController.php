<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));

        $allowedStatuses = ['', 'pending', 'published', 'hidden'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        $courses = $this->courseService->getAdminCoursesList($search, $status);

        return view('admin.courses.index', compact('courses', 'search', 'status'));
    }

    public function approve(Course $course): RedirectResponse
    {
        $this->courseService->approveCourse($course);

        return back()->with('success', 'Đã duyệt công khai khóa học "' . $course->title . '".');
    }

    public function hide(Course $course): RedirectResponse
    {
        $this->courseService->hideCourse($course);

        return back()->with('success', 'Đã ẩn khóa học vi phạm "' . $course->title . '" khỏi trang công khai.');
    }
}