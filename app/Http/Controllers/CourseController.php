<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CourseController extends Controller
{
    // Danh sách khóa học công khai (HT-01)
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        $query = Course::query()
            ->with(['category:id,name', 'teacher:id,name'])
            ->withCount('lessons')
            ->whereIn('status', ['published', 'public']);

        // Tìm theo tên khóa học
        if ($request->filled('keyword')) {
            $query->where(
                'title',
                'like',
                '%' . trim($request->keyword) . '%'
            );
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo giảng viên
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Phân trang 9 khóa học/trang
        $courses = $query->latest()
            ->paginate(9)
            ->withQueryString();

        return view('courses.index', compact(
            'courses',
            'categories',
            'teachers'
        ));
    }

// Chi tiết khóa học (HT-02)
    public function show(Course $course): View
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        // Chỉ Admin mới xem được trang chi tiết khi khóa học đang bị ẩn
        abort_unless(
            in_array($course->status, ['published', 'public']) || $isAdmin,
            404
        );

        $course->load(['category', 'teacher', 'lessons']);

        $hasEnrollmentsTable = Schema::hasTable('enrollments');

        $enrollmentsCount = $hasEnrollmentsTable
            ? DB::table('enrollments')
                ->where('course_id', $course->id)
                ->count()
            : 0;

        $course->enrollments_count = $enrollmentsCount;

        $isEnrolled = false;

        if (auth()->check() && $hasEnrollmentsTable) {
            $isEnrolled = DB::table('enrollments')
                ->where('course_id', $course->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('courses.show', compact(
            'course',
            'isEnrolled',
            'enrollmentsCount'
        ));
    }

    // Ghi danh khóa học (HT-04)
    public function enroll(Course $course)
    {
        // Chặn Giảng viên và Admin ghi danh
        if (auth()->user()->role !== 'student') {
            abort(403, 'Chỉ tài khoản Học viên mới có thể ghi danh khóa học.');
        }

        $userId = auth()->id();

        $exists = DB::table('enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Bạn đã ghi danh khóa học này rồi.'
            );
        }

        DB::table('enrollments')->insert([
            'user_id' => $userId,
            'course_id' => $course->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with(
            'success',
            'Ghi danh khóa học thành công!'
        );
    }

    // Danh sách khóa học đã ghi danh
    public function myCourses(): View
    {
        $enrollments = DB::table('enrollments')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get()
            ->keyBy('course_id');

        $courses = Course::with([
                'category:id,name',
                'teacher:id,name'
            ])
            ->withCount('lessons')
            ->whereIn('id', $enrollments->keys())
            ->get();

        return view('courses.my-courses', compact(
            'courses',
            'enrollments'
        ));
    }

    // Vào học bài giảng (HT-05)
    public function learn(Course $course, $lessonId = null)
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        abort_unless(in_array($course->status, ['published', 'public']) || $isAdmin, 404);

        $userId = auth()->id();

        // 1. Kiểm tra quyền truy cập ở server (HT-NFR2)
        $isEnrolled = Schema::hasTable('enrollments')
            && DB::table('enrollments')
                ->where('course_id', $course->id)
                ->where('user_id', $userId)
                ->exists();

        $isOwnerOrAdmin = ($course->teacher_id === $userId) || $isAdmin;

        if (!$isEnrolled && !$isOwnerOrAdmin) {
            return redirect()
                ->route('courses.show', $course->id)
                ->with('error', 'Bạn cần ghi danh khóa học trước khi vào học.');
        }

        // 2. Lấy danh sách bài học của khóa
        $course->load(['category', 'teacher', 'lessons']);
        $lessons = $course->lessons;

        if ($lessons->isEmpty()) {
            return redirect()
                ->route('courses.show', $course->id)
                ->with('error', 'Khóa học này hiện chưa có bài học nào.');
        }

        // 3. Xác định bài học đang xem (mặc định là bài đầu tiên nếu chưa chọn)
        $currentLesson = $lessonId
            ? $lessons->firstWhere('id', (int) $lessonId)
            : $lessons->first();

        abort_unless($currentLesson !== null, 404);

        // 4. Tính bài trước / bài sau
        $currentIndex = $lessons->search(fn ($item) => $item->id === $currentLesson->id);
        $prevLesson = $currentIndex > 0 ? $lessons[$currentIndex - 1] : null;
        $nextLesson = ($currentIndex !== false && $currentIndex < $lessons->count() - 1)
            ? $lessons[$currentIndex + 1]
            : null;

        // 5. Lấy danh sách ID các bài học viên đã hoàn thành từ bảng lesson_progress
        $completedLessonIds = [];
        if (Schema::hasTable('lesson_progress')) {
            $progressQuery = DB::table('lesson_progress')
                ->where('user_id', $userId)
                ->whereIn('lesson_id', $lessons->pluck('id'));

            if (Schema::hasColumn('lesson_progress', 'is_completed')) {
                $progressQuery->where('is_completed', true);
            }

            $completedLessonIds = $progressQuery->pluck('lesson_id')->toArray();
        }

        return view('courses.learn', compact(
            'course',
            'lessons',
            'currentLesson',
            'prevLesson',
            'nextLesson',
            'completedLessonIds'
        ));
    }

    // Đánh dấu hoàn thành bài học (HT-05)
    public function completeLesson(Course $course, $lessonId)
    {
        // Chỉ Học viên mới lưu tiến độ hoàn thành bài học
        if (auth()->user()->role !== 'student') {
            abort(403, 'Chỉ tài khoản Học viên mới có thể đánh dấu hoàn thành bài học.');
        }

        $userId = auth()->id();

        // Kiểm tra quyền ghi danh ở server (HT-NFR2)
        $isEnrolled = Schema::hasTable('enrollments')
            && DB::table('enrollments')
                ->where('course_id', $course->id)
                ->where('user_id', $userId)
                ->exists();

        if (!$isEnrolled) {
            abort(403, 'Bạn chưa ghi danh khóa học này.');
        }

        // Đảm bảo bài học thuộc đúng khóa học (Dòng này định nghĩa biến $lesson)
        $lesson = $course->lessons()->where('id', (int) $lessonId)->firstOrFail();

        // Ghi vào bảng lesson_progress (chống trùng lặp)
        if (Schema::hasTable('lesson_progress')) {
            $exists = DB::table('lesson_progress')
                ->where('user_id', $userId)
                ->where('lesson_id', $lesson->id)
                ->exists();

            if (!$exists) {
                $data = [
                    'user_id' => $userId,
                    'lesson_id' => $lesson->id,
                ];

                if (Schema::hasColumn('lesson_progress', 'is_completed')) {
                    $data['is_completed'] = true;
                }
                if (Schema::hasColumn('lesson_progress', 'completed_at')) {
                    $data['completed_at'] = now();
                }
                if (Schema::hasColumn('lesson_progress', 'created_at')) {
                    $data['created_at'] = now();
                }
                if (Schema::hasColumn('lesson_progress', 'updated_at')) {
                    $data['updated_at'] = now();
                }

                DB::table('lesson_progress')->insert($data);
            }
        }

        return redirect()
            ->route('courses.learn', [$course->id, $lesson->id])
            ->with('success', 'Đã đánh dấu hoàn thành bài học!');
    }
    
}