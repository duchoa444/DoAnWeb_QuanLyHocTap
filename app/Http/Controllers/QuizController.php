<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\QuizService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuizController extends Controller
{
    protected QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Hiển thị trang làm bài trắc nghiệm (HT-06)
     */
    public function show(int $courseId)
    {
        $course = Course::findOrFail($courseId);

        // Chặn nếu học viên chưa hoàn thành tất cả bài học của khóa
        if (!$this->hasCompletedAllLessons($course, (int) Auth::id())) {
            return redirect()
                ->route('courses.learn', $course->id)
                ->with('error', 'Bạn cần hoàn thành tất cả bài học trong khóa học trước khi làm bài trắc nghiệm.');
        }

        try {
            $data = $this->quizService->start((int) Auth::id(), $courseId);
            return view('student.quiz.take', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function start(int $courseId)
    {
        return $this->show($courseId);
    }
    /**
     * Xử lý nộp bài trắc nghiệm (HT-06)
     */
public function submit(Request $request, int $attemptId)
    {
        $answers = $request->input('answers', []);
        if (!is_array($answers)) {
            $answers = [];
        }

        try {
            $result = $this->quizService->submit($attemptId, (int) Auth::id(), $answers);

            session()->flash('submitted_answers_' . $attemptId, $answers);

            return redirect()
                ->route('student.quizzes.result', ['attemptId' => $attemptId])
                ->with('success', 'Nộp bài thành công! Điểm của bạn: ' . $result['score'] . '/10');
        } catch (Exception $e) {
            $attempt = DB::table('quiz_attempts')->where('id', $attemptId)->first();
            if ($attempt && $attempt->submitted_at !== null) {
                return redirect()
                    ->route('student.quizzes.result', ['attemptId' => $attemptId])
                    ->with('error', $e->getMessage());
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Lỗi nộp bài: ' . $e->getMessage());
        }
    }

    public function result(int $attemptId)
    {
        try {
            $data = $this->quizService->getResult($attemptId, (int) Auth::id());
            $data['user_answers'] = session('submitted_answers_' . $attemptId, []);
            return view('student.quiz.result', $data);
        } catch (Exception $e) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Không thể hiển thị kết quả thi: ' . $e->getMessage());
        }
    }
    /**
     * Kiểm tra học viên đã hoàn thành 100% bài học của khóa chưa
     */
    private function hasCompletedAllLessons(Course $course, int $userId): bool
    {
        $lessonIds = $course->lessons()->pluck('id');

        if ($lessonIds->isEmpty() || !Schema::hasTable('lesson_progress')) {
            return false;
        }

        $query = DB::table('lesson_progress')
            ->where('user_id', $userId)
            ->whereIn('lesson_id', $lessonIds);

        if (Schema::hasColumn('lesson_progress', 'is_completed')) {
            $query->where('is_completed', true);
        }

        $completedCount = $query->distinct('lesson_id')->count('lesson_id');

        return $completedCount >= $lessonIds->count();
    }
}