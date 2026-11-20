<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    // Danh sách khóa học của giảng viên
    public function index(Request $request)
    {
        $courses = $this->courseService->getTeacherCoursesList(
            Auth::id(),
            $request->input('search', '')
        );

        return view('teacher.courses.index', compact('courses'));
    }

    // Form tạo khóa học
    public function create()
    {
        $categories = Category::all();

        return view('teacher.courses.create', compact('categories'));
    }

    // Lưu khóa học mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quiz_minutes' => 'required|integer|min:1',
        ]);

        $data['status'] = 'draft';

        $this->courseService->createTeacherCourse(
            Auth::id(),
            $data
        );

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Tạo khóa học thành công!');
    }

    // Form chỉnh sửa
    public function edit(int $id)
    {
        $course = $this->courseService->findTeacherCourse(
            $id,
            Auth::id()
        );

        $categories = Category::all();

        return view('teacher.courses.edit', compact('course', 'categories'));
    }

    // Cập nhật khóa học
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quiz_minutes' => 'required|integer|min:1',
        ]);

        $this->courseService->updateTeacherCourse(
            $id,
            Auth::id(),
            $data
        );

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Cập nhật khóa học thành công!');
    }

    // Xóa khóa học
    public function destroy(int $id)
    {
        $this->courseService->deleteTeacherCourse(
            $id,
            Auth::id()
        );

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Xóa khóa học thành công!');
    }
}
