<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    // Lưu khóa học mới (kèm danh sách bài học và câu hỏi đề thi)
    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'nullable|in:draft,pending',
            'quiz_minutes' => 'required|integer|min:1|max:180',
            'max_attempts' => 'nullable|integer|min:1|max:20',
            'lessons'      => 'nullable|array',
            'questions'    => 'nullable|array',
        ]);

        $courseId = DB::transaction(function () use ($request) {
            $courseData = [
                'teacher_id'   => Auth::id(),
                'category_id'  => $request->input('category_id'),
                'title'        => $request->input('title'),
                'description'  => $request->input('description'),
                'quiz_minutes' => $request->input('quiz_minutes', 30),
                'status'       => $request->input('status', 'pending'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            if (Schema::hasColumn('courses', 'max_attempts')) {
                $courseData['max_attempts'] = $request->input('max_attempts', 3);
            }

            $newCourseId = DB::table('courses')->insertGetId($courseData);

            $this->syncLessons($newCourseId, $request->input('lessons', []));
            $this->storeQuestions($newCourseId, $request->input('questions', []));

            return $newCourseId;
        });

        return redirect()
            ->route('teacher.questions.index', $courseId)
            ->with('success', 'Tạo khóa học thành công! Bạn có thể tiếp tục thêm hoặc chỉnh sửa câu hỏi đề thi tại đây.');
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
        $course = $this->courseService->findTeacherCourse(
            $id,
            Auth::id()
        );

        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'nullable|string',
            'quiz_minutes' => 'required|integer|min:1|max:180',
            'max_attempts' => 'nullable|integer|min:1|max:20',
            'lessons'      => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $course) {
            $courseData = [
                'category_id'  => $request->input('category_id'),
                'title'        => $request->input('title'),
                'description'  => $request->input('description'),
                'quiz_minutes' => $request->input('quiz_minutes', $course->quiz_minutes ?? 30),
                'status'       => $request->input('status', $course->status),
                'updated_at'   => now(),
            ];

            if (Schema::hasColumn('courses', 'max_attempts')) {
                $courseData['max_attempts'] = $request->input('max_attempts', $course->max_attempts ?? 3);
            }

            DB::table('courses')->where('id', $course->id)->update($courseData);

            $this->syncLessons($course->id, $request->input('lessons', []));
        });

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

    // Hàm hỗ trợ lưu & cập nhật danh sách bài học
    private function syncLessons(int $courseId, array $lessons): void
    {
        if (!Schema::hasTable('lessons')) {
            return;
        }

        $columns = Schema::getColumnListing('lessons');
        $keepIds = [];

        foreach ($lessons as $idx => $lesson) {
            $title = trim($lesson['title'] ?? '');
            if ($title === '') {
                continue;
            }

            $row = [
                'course_id'  => $courseId,
                'title'      => $title,
                'updated_at' => now(),
            ];

            if (in_array('content', $columns)) {
                $row['content'] = $lesson['content'] ?? null;
            } elseif (in_array('description', $columns)) {
                $row['description'] = $lesson['content'] ?? null;
            }

            if (in_array('video_url', $columns)) {
                $row['video_url'] = $lesson['video_url'] ?? null;
            } elseif (in_array('url', $columns)) {
                $row['url'] = $lesson['video_url'] ?? null;
            }

            if (in_array('order', $columns)) {
                $row['order'] = $idx + 1;
            } elseif (in_array('position', $columns)) {
                $row['position'] = $idx + 1;
            }

            if (!empty($lesson['id'])) {
                DB::table('lessons')
                    ->where('id', $lesson['id'])
                    ->where('course_id', $courseId)
                    ->update($row);
                $keepIds[] = (int) $lesson['id'];
            } else {
                $row['created_at'] = now();
                $keepIds[] = DB::table('lessons')->insertGetId($row);
            }
        }

        $deleteQuery = DB::table('lessons')->where('course_id', $courseId);
        if (!empty($keepIds)) {
            $deleteQuery->whereNotIn('id', $keepIds);
        }
        $deleteQuery->delete();
    }

    // Hàm hỗ trợ lưu danh sách câu hỏi trắc nghiệm khi tạo mới khóa học
    private function storeQuestions(int $courseId, array $questions): void
    {
        if (empty($questions) || !Schema::hasTable('questions')) {
            return;
        }

        $columns = Schema::getColumnListing('questions');

        foreach ($questions as $q) {
            $text = trim($q['content'] ?? '');
            if ($text === '') {
                continue;
            }

            $qData = [
                'course_id'  => $courseId,
                'option_a'   => $q['option_a'] ?? '',
                'option_b'   => $q['option_b'] ?? '',
                'option_c'   => $q['option_c'] ?? '',
                'option_d'   => $q['option_d'] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            foreach (['content', 'question_text', 'question'] as $col) {
                if (in_array($col, $columns)) {
                    $qData[$col] = $text;
                    break;
                }
            }

            foreach (['correct_answer', 'correct_option', 'answer'] as $col) {
                if (in_array($col, $columns)) {
                    $qData[$col] = $q['correct_answer'] ?? 'A';
                    break;
                }
            }

            DB::table('questions')->insert($qData);
        }
    }
}