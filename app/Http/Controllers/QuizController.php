<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        try {
            $data = $this->quizService->start((int) Auth::id(), $courseId);
            return view('student.quiz.take', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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

            // Lưu tạm đáp án học viên đã chọn vào session để đối chiếu trên trang kết quả
            session()->flash('submitted_answers_' . $attemptId, $answers);

            return redirect()
                ->route('student.quizzes.result', ['attemptId' => $attemptId])
                ->with('success', 'Nộp bài thành công! Điểm của bạn: ' . $result['score'] . '/10');
        } catch (Exception $e) {
            $attempt = \Illuminate\Support\Facades\DB::table('quiz_attempts')->where('id', $attemptId)->first();
            if ($attempt && $attempt->submitted_at !== null) {
                return redirect()
                    ->route('student.quizzes.result', ['attemptId' => $attemptId])
                    ->with('error', $e->getMessage());
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hiển thị trang kết quả, đáp án đúng & số lượt còn lại (HT-06)
     */
    public function result(int $attemptId)
    {
        try {
            $data = $this->quizService->getResult((int) Auth::id(), $attemptId);
            $data['user_answers'] = session('submitted_answers_' . $attemptId, []);
            return view('student.quiz.result', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}