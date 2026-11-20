<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Exception;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(protected QuizService $quizService) {}

    public function start(int $courseId)
    {
        try {
            $data = $this->quizService->start(auth()->id(), $courseId);
            return view('quiz.take', $data);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function submit(Request $request, int $attemptId)
    {
        try {
            $result = $this->quizService->submit(
                $attemptId,
                auth()->id(),
                $request->input('answers', [])
            );
            return view('quiz.result', $result)->with('success', 'Nộp bài thành công!');
        } catch (Exception $e) {
            return redirect('/my-courses')->with('error', $e->getMessage());
        }
    }
}