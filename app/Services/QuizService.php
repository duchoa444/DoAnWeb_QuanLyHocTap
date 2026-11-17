<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\QuizAttempt;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizService
{
    /**
     * Bắt đầu lượt làm bài trắc nghiệm (HT-06, HT-NFR1, HT-NFR2)
     */
    public function start(int $userId, int $courseId): array
    {
        return DB::transaction(function () use ($userId, $courseId) {
            $course = Course::findOrFail($courseId);

            // 1. Chỉ học viên đã ghi danh mới được làm bài (HT-NFR2)
            $isEnrolled = Enrollment::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->exists();

            if (!$isEnrolled) {
                throw new Exception('Bạn cần ghi danh khóa học trước khi làm bài trắc nghiệm.', 403);
            }

            // 2. Lấy câu hỏi 4 lựa chọn, TUYỆT ĐỐI KHÔNG lấy cột correct_option (HT-NFR1)
            $questions = Question::where('course_id', $courseId)
                ->select('id', 'course_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d')
                ->get();

            if ($questions->isEmpty()) {
                throw new Exception('Khóa học này hiện chưa có câu hỏi trắc nghiệm.', 404);
            }

            $minutes = $course->quiz_minutes ?? 15;
            $now = Carbon::now();

            // 3. Nếu đang có lượt làm dở (chưa nộp) và vẫn còn hạn -> trả về làm tiếp (tránh F5 mất lượt)
            $activeAttempt = QuizAttempt::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->whereNull('submitted_at')
                ->lockForUpdate()
                ->first();

            if ($activeAttempt) {
                $deadline = Carbon::parse($activeAttempt->started_at)->addMinutes($minutes)->addMinute();
                if ($now->lte($deadline)) {
                    $attemptCount = QuizAttempt::where('user_id', $userId)
                        ->where('course_id', $courseId)
                        ->count();

                    return [
                        'course'       => $course,
                        'attempt'      => $activeAttempt,
                        'attempt_no'   => $attemptCount,
                        'quiz_minutes' => $minutes,
                        'questions'    => $questions,
                    ];
                }

                // Nếu lượt cũ đã quá hạn mà chưa nộp -> tự động chốt 0 điểm
                $activeAttempt->update([
                    'submitted_at' => $deadline,
                    'score'        => 0,
                ]);
            }

            // 4. Giới hạn tối đa 3 lần làm bài (HT-06)
            $attemptCount = QuizAttempt::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->lockForUpdate()
                ->count();

            if ($attemptCount >= 3) {
                throw new Exception('Bạn đã sử dụng hết tối đa 3 lượt làm bài cho khóa học này.', 403);
            }

            // 5. Tạo lượt làm bài mới, chốt giờ bắt đầu tại Server
            $attempt = QuizAttempt::create([
                'user_id'    => $userId,
                'course_id'  => $courseId,
                'started_at' => $now,
            ]);

            return [
                'course'       => $course,
                'attempt'      => $attempt,
                'attempt_no'   => $attemptCount + 1,
                'quiz_minutes' => $minutes,
                'questions'    => $questions,
            ];
        });
    }

    /**
     * Nộp bài & chấm điểm tự động tại Server (HT-06, HT-NFR1)
     */
    public function submit(int $attemptId, int $userId, array $answers): array
    {
        return DB::transaction(function () use ($attemptId, $userId, $answers) {
            $attempt = QuizAttempt::with('course')->lockForUpdate()->findOrFail($attemptId);

            if ($attempt->user_id !== $userId) {
                throw new Exception('Bạn không có quyền nộp bài thi này.', 403);
            }

            if ($attempt->submitted_at !== null) {
                throw new Exception('Lượt làm bài này đã được nộp trước đó.', 400);
            }

            // Kiểm tra thời gian tại Server: started_at + quiz_minutes + 1 phút dung sai (HT-NFR1)
            $now = Carbon::now();
            $minutes = $attempt->course->quiz_minutes ?? 15;
            $deadline = Carbon::parse($attempt->started_at)->addMinutes($minutes)->addMinute();

            if ($now->gt($deadline)) {
                $attempt->update([
                    'submitted_at' => $now,
                    'score'        => 0,
                ]);
                throw new Exception('Đã quá thời gian làm bài quy định, bài nộp bị từ chối.', 422);
            }

            // Chấm điểm tự động tại Server
            $questions = Question::where('course_id', $attempt->course_id)->get();
            $totalQuestions = $questions->count();
            $correctCount = 0;

            foreach ($questions as $question) {
                $selected = strtoupper((string) ($answers[$question->id] ?? ''));
                if ($selected !== '' && $selected === strtoupper($question->correct_option)) {
                    $correctCount++;
                }
            }

            $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 10, 2) : 0;

            $attempt->update([
                'submitted_at' => $now,
                'score'        => $score,
            ]);

            // Lấy điểm cao nhất trong tối đa 3 lần làm bài (HT-06)
            $bestScore = QuizAttempt::where('user_id', $userId)
                ->where('course_id', $attempt->course_id)
                ->whereNotNull('submitted_at')
                ->max('score');

            return [
                'attempt'         => $attempt,
                'score'           => $score,
                'best_score'      => $bestScore,
                'correct_count'   => $correctCount,
                'total_questions' => $totalQuestions,
            ];
        });
    }
}