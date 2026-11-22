<?php

namespace App\Services;

use App\Models\Course;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuizService
{
    /**
     * Khởi tạo hoặc tiếp tục lượt làm bài trắc nghiệm (HT-06)
     */
    public function start(int $userId, int $courseId): array
    {
        $course = Course::findOrFail($courseId);

        $questions = $this->getNormalizedQuestions($courseId);
        if ($questions->isEmpty()) {
            throw new Exception('Khóa học này hiện chưa có câu hỏi trắc nghiệm.');
        }

        $attemptCols = Schema::getColumnListing('quiz_attempts');
        $userCol = in_array('user_id', $attemptCols) ? 'user_id' : 'student_id';
        $startCol = in_array('started_at', $attemptCols) ? 'started_at' : 'created_at';

        $maxAttempts = (int) ($course->max_attempts ?? 3);
        if ($maxAttempts < 1) {
            $maxAttempts = 3;
        }

        $quizMinutes = (int) ($course->quiz_minutes ?? 30);
        if ($quizMinutes < 1) {
            $quizMinutes = 30;
        }

        // Kiểm tra xem học viên có lượt làm bài nào đang mở (chưa nộp) không
        $activeAttempt = DB::table('quiz_attempts')
            ->where('course_id', $courseId)
            ->where($userCol, $userId)
            ->whereNull('submitted_at')
            ->orderByDesc('id')
            ->first();

        if ($activeAttempt) {
            $startedAt = Carbon::parse($activeAttempt->{$startCol} ?? now());
            $deadline = $startedAt->copy()->addMinutes($quizMinutes);

            // Nếu lượt cũ đã quá giờ mà chưa nộp, tự động chốt 0 điểm để mở lượt mới
            if (now()->greaterThanOrEqualTo($deadline)) {
                $closeData = [
                    'score'        => 0,
                    'submitted_at' => $deadline,
                ];
                if (in_array('updated_at', $attemptCols)) {
                    $closeData['updated_at'] = now();
                }

                DB::table('quiz_attempts')
                    ->where('id', $activeAttempt->id)
                    ->update($closeData);

                $activeAttempt = null;
            }
        }

        // Đếm số lượt đã hoàn thành nộp bài
        $submittedCount = DB::table('quiz_attempts')
            ->where('course_id', $courseId)
            ->where($userCol, $userId)
            ->whereNotNull('submitted_at')
            ->count();

        if ($activeAttempt) {
            $attempt = $activeAttempt;
            $startedAt = Carbon::parse($attempt->{$startCol} ?? now());
            $remainingSeconds = max(1, (int) now()->diffInSeconds($startedAt->copy()->addMinutes($quizMinutes), false));
        } else {
            if ($submittedCount >= $maxAttempts) {
                throw new Exception("Bạn đã sử dụng hết {$maxAttempts}/{$maxAttempts} lượt làm bài kiểm tra cho khóa học này.");
            }

            $insertData = [
                'course_id' => $courseId,
                $userCol    => $userId,
            ];

            if (in_array('started_at', $attemptCols)) {
                $insertData['started_at'] = now();
            }
            if (in_array('created_at', $attemptCols)) {
                $insertData['created_at'] = now();
            }
            if (in_array('updated_at', $attemptCols)) {
                $insertData['updated_at'] = now();
            }

            $newId = DB::table('quiz_attempts')->insertGetId($insertData);
            $attempt = DB::table('quiz_attempts')->where('id', $newId)->first();
            $remainingSeconds = $quizMinutes * 60;
        }

        $attemptNo = $submittedCount + 1;

        return [
            'course'            => $course,
            'attempt'           => $attempt,
            'attemptId'         => $attempt->id,
            'attempt_id'        => $attempt->id,
            'questions'         => $questions,
            'quizMinutes'       => $quizMinutes,
            'quiz_minutes'      => $quizMinutes,
            'remainingSeconds'  => $remainingSeconds,
            'remaining_seconds' => $remainingSeconds,
            'attemptNumber'     => $attemptNo,
            'attempt_number'    => $attemptNo,
            'attempt_no'        => $attemptNo,
            'maxAttempts'       => $maxAttempts,
            'max_attempts'      => $maxAttempts,
        ];
    }

    /**
     * Chấm điểm và lưu kết quả nộp bài trắc nghiệm (HT-06)
     */
    public function submit(int $attemptId, int $userId, array $answers): array
    {
        $attempt = DB::table('quiz_attempts')->where('id', $attemptId)->first();

        if (!$attempt) {
            throw new Exception('Không tìm thấy lượt làm bài kiểm tra.');
        }

        $ownerId = (int) ($attempt->user_id ?? $attempt->student_id ?? 0);
        if ($ownerId !== $userId) {
            throw new Exception('Bạn không có quyền nộp lượt làm bài này.');
        }

        if ($attempt->submitted_at !== null) {
            throw new Exception('Lượt làm bài này đã được nộp trước đó.');
        }
        // Kiểm tra quá thời gian làm bài (cộng 30 giây ân hạn trễ mạng)
        $course = Course::find($attempt->course_id);
        $quizMinutes = (int) ($course->quiz_minutes ?? 30);
        if ($quizMinutes < 1) {
            $quizMinutes = 30;
        }
        $startedAtRaw = $attempt->started_at ?? $attempt->created_at ?? null;
        if ($startedAtRaw !== null) {
            $deadline = Carbon::parse($startedAtRaw)->addMinutes($quizMinutes)->addSeconds(30);
            if (now()->greaterThan($deadline)) {
                throw new Exception('Đã quá thời gian làm bài quy định, bài nộp bị từ chối.');
            }
        }

        $questions = $this->getNormalizedQuestions((int) $attempt->course_id);
        $totalQuestions = $questions->count();
        $correctCount = 0;

        foreach ($questions as $question) {
            $chosen = strtoupper(trim((string) ($answers[$question->id] ?? '')));
            $correct = strtoupper(trim((string) $question->correct_answer));

            if ($chosen !== '' && $chosen === $correct) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0
            ? round(($correctCount / $totalQuestions) * 10, 2)
            : 0.0;

        $attemptCols = Schema::getColumnListing('quiz_attempts');
        $updateData = [
            'score'        => $score,
            'submitted_at' => now(),
        ];

        if (in_array('correct_count', $attemptCols)) {
            $updateData['correct_count'] = $correctCount;
        }
        if (in_array('total_questions', $attemptCols)) {
            $updateData['total_questions'] = $totalQuestions;
        }
        if (in_array('answers', $attemptCols)) {
            $updateData['answers'] = json_encode($answers);
        }
        if (in_array('updated_at', $attemptCols)) {
            $updateData['updated_at'] = now();
        }

        DB::table('quiz_attempts')
            ->where('id', $attemptId)
            ->update($updateData);

        return [
            'attempt_id'      => $attemptId,
            'score'           => $score,
            'correct_count'   => $correctCount,
            'total_questions' => $totalQuestions,
        ];
    }

    /**
     * Lấy chi tiết kết quả, đáp án đúng và số lượt thi còn lại (HT-06)
     */
    public function getResult(int $attemptId, int $userId): array
    {
        $attempt = DB::table('quiz_attempts')->where('id', $attemptId)->first();

        if (!$attempt) {
            throw new Exception('Không tìm thấy kết quả lượt làm bài.');
        }

        $ownerId = (int) ($attempt->user_id ?? $attempt->student_id ?? 0);
        if ($ownerId !== $userId) {
            throw new Exception('Bạn không có quyền xem điểm lượt làm bài này.');
        }

        if ($attempt->submitted_at === null) {
            throw new Exception('Lượt làm bài này chưa được nộp.');
        }

        $course = Course::findOrFail($attempt->course_id);
        $questions = $this->getNormalizedQuestions((int) $course->id);

        $totalQuestions = $questions->count();
        $score = (float) ($attempt->score ?? 0);
        $correctCount = isset($attempt->correct_count)
            ? (int) $attempt->correct_count
            : ($totalQuestions > 0 ? (int) round(($score / 10) * $totalQuestions) : 0);

        $attemptCols = Schema::getColumnListing('quiz_attempts');
        $userCol = in_array('user_id', $attemptCols) ? 'user_id' : 'student_id';

        $maxAttempts = (int) ($course->max_attempts ?? 3);
        if ($maxAttempts < 1) {
            $maxAttempts = 3;
        }

      $allAttempts = DB::table('quiz_attempts')
            ->where('course_id', $course->id)
            ->where($userCol, $userId)
            ->whereNotNull('submitted_at')
            ->orderBy('id')
            ->get();

        $usedAttempts = $allAttempts->count();
        $bestScore = (float) ($allAttempts->max('score') ?? $score);
        $remainingAttempts = max(0, $maxAttempts - $usedAttempts);

        $savedAnswers = [];
        if (isset($attempt->answers) && is_string($attempt->answers)) {
            $decoded = json_decode($attempt->answers, true);
            if (is_array($decoded)) {
                $savedAnswers = $decoded;
            }
        }

        return [
            'course'             => $course,
            'attempt'            => $attempt,
            'attempts'           => $allAttempts,
            'questions'          => $questions,
            'score'              => $score,
            'bestScore'          => $bestScore,
            'best_score'         => $bestScore,
            'correctCount'       => $correctCount,
            'correct_count'      => $correctCount,
            'totalQuestions'     => $totalQuestions,
            'total_questions'    => $totalQuestions,
            'maxAttempts'        => $maxAttempts,
            'max_attempts'       => $maxAttempts,
            'usedAttempts'       => $usedAttempts,
            'used_attempts'      => $usedAttempts,
            'attempt_no'         => $usedAttempts,
            'attemptNumber'      => $usedAttempts,
            'remainingAttempts'  => $remainingAttempts,
            'remaining_attempts' => $remainingAttempts,
            'attempts_left'      => $remainingAttempts,
            'passed'             => $score >= 5.0,
            'saved_answers'      => $savedAnswers,
        ];
    }

    /**
     * Truy vấn và chuẩn hóa tên thuộc tính câu hỏi của khóa học
     */
    private function getNormalizedQuestions(int $courseId)
    {
        if (!Schema::hasTable('questions')) {
            return collect();
        }

        return DB::table('questions')
            ->where('course_id', $courseId)
            ->orderBy('id')
            ->get()
            ->map(function ($q) {
                $text = $q->content ?? $q->question_text ?? $q->question ?? '';
                $q->content = $text;
                $q->question_text = $text;
                $q->correct_answer = strtoupper((string) ($q->correct_answer ?? $q->correct_option ?? $q->answer ?? 'A'));
                return $q;
            });
    }
}