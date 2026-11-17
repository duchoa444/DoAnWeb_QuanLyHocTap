<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
}