<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    // Danh sách khóa học
    public function index()
    {
        $courses = $this->courseService->getCourses(
            auth()->id()
        );

        return view('courses.index', compact('courses'));
    }

    // Form thêm khóa học
    public function create()
    {
    $categories = Category::all();

    return view('courses.create', compact('categories'));
    }

    // Lưu khóa học mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quiz_minutes' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);

        $this->courseService->createCourse(
            $data,
            auth()->id()
        );

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Thêm khóa học thành công!');
    }

    // Chi tiết khóa học
    public function show($id)
    {
        $course = $this->courseService->getCourse(
            $id,
            auth()->id()
        );

        return view('courses.show', compact('course'));
    }

    // Form chỉnh sửa
    public function edit($id)
{
    $course = $this->courseService->getCourse($id, auth()->id());
    $categories = Category::all();

    return view('courses.edit', compact('course', 'categories'));
}

    // Cập nhật khóa học
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quiz_minutes' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
        ]);

        $this->courseService->updateCourse(
            $id,
            auth()->id(),
            $data
        );

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Cập nhật khóa học thành công!');
    }

    // Xóa khóa học
    public function destroy($id)
    {
        $this->courseService->deleteCourse(
            $id,
            auth()->id()
        );

        return redirect()
            ->route('teacher.courses.index')
            ->with('success', 'Xóa khóa học thành công!');
    }
}
