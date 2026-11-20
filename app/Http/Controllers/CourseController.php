<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        $query = Course::query()
            ->with(['category:id,name', 'teacher:id,name'])
            ->withCount('lessons')
            ->whereIn('status', ['published', 'public']);

        // Tìm theo tên khóa học
        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . trim($request->keyword) . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo giảng viên
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Phân trang server: 9 khóa/trang
        $courses = $query->latest()->paginate(9)->withQueryString();

        return view('courses.index', compact('courses', 'categories', 'teachers'));
    }
public function show(Course $course)
    {
        abort_unless(in_array($course->status, ['published', 'public']), 404);
        $course->load(['category', 'teacher', 'lessons']);

        $hasEnrollmentsTable = \Illuminate\Support\Facades\Schema::hasTable('enrollments');

        $enrollmentsCount = $hasEnrollmentsTable
            ? DB::table('enrollments')->where('course_id', $course->id)->count()
            : 0;

        // Gán thêm vào thuộc tính của $course để show.blade.php hiển thị đúng số học viên
        $course->enrollments_count = $enrollmentsCount;

        $isEnrolled = false;
        if (auth()->check() && $hasEnrollmentsTable) {
            $isEnrolled = DB::table('enrollments')
                ->where('course_id', $course->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled', 'enrollmentsCount'));
    }
public function enroll(Course $course)
{
    $userId = auth()->id();

    $exists = DB::table('enrollments')
        ->where('user_id', $userId)
        ->where('course_id', $course->id)
        ->exists();

    if ($exists) {
        return back()->with('error', 'Bạn đã ghi danh khóa học này rồi.');
    }

    DB::table('enrollments')->insert([
        'user_id'    => $userId,
        'course_id'  => $course->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Ghi danh khóa học thành công!');
}
public function myCourses(): View
    {
        $enrollments = DB::table('enrollments')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get()
            ->keyBy('course_id');

        $courses = Course::with(['category:id,name', 'teacher:id,name'])
            ->withCount('lessons')
            ->whereIn('id', $enrollments->keys())
            ->get();

        return view('courses.my-courses', compact('courses', 'enrollments'));
    }
}