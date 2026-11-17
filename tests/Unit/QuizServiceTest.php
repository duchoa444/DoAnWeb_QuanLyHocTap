<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\QuizService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QuizServiceTest extends TestCase
{
    use RefreshDatabase;

    private QuizService $quizService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quizService = new QuizService();
    }

    private function seedQuizData(int $totalQuestions = 10): array
    {
        $teacher = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create(['role' => 'student']);

        $categoryId = DB::table('categories')->insertGetId([
            'name'       => 'Lập trình Web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $courseId = DB::table('courses')->insertGetId([
            'title'        => 'Khóa học kiểm thử Laravel',
            'description'  => 'Mô tả khóa học',
            'category_id'  => $categoryId,
            'teacher_id'   => $teacher->id,
            'status'       => 'published',
            'quiz_minutes' => 15,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        DB::table('enrollments')->insert([
            'user_id'    => $student->id,
            'course_id'  => $courseId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $questionIds = [];
        for ($i = 1; $i <= $totalQuestions; $i++) {
            $questionIds[] = DB::table('questions')->insertGetId([
                'course_id'      => $courseId,
                'question_text'  => "Câu hỏi số {$i}",
                'option_a'       => 'Đáp án A',
                'option_b'       => 'Đáp án B',
                'option_c'       => 'Đáp án C',
                'option_d'       => 'Đáp án D',
                'correct_option' => 'A',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        return [$student, $courseId, $questionIds];
    }

    public function test_cham_diem_chinh_xac_8_tren_10_cau_dung(): void
    {
        [$student, $courseId, $questionIds] = $this->seedQuizData(10);

        $started = $this->quizService->start($student->id, $courseId);
        $attemptId = $started['attempt']->id;

        $answers = [];
        foreach ($questionIds as $index => $qId) {
            $answers[$qId] = ($index < 8) ? 'A' : 'B';
        }

        $result = $this->quizService->submit($attemptId, $student->id, $answers);

        $this->assertEquals(8.0, $result['score']);
        $this->assertEquals(8, $result['correct_count']);
        $this->assertEquals(10, $result['total_questions']);
    }

    public function test_nop_bai_sau_han_bi_tu_choi(): void
    {
        [$student, $courseId, $questionIds] = $this->seedQuizData(5);

        $started = $this->quizService->start($student->id, $courseId);
        $attempt = $started['attempt'];

        DB::table('quiz_attempts')
            ->where('id', $attempt->id)
            ->update([
                'started_at' => Carbon::now()->subMinutes(17),
            ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Đã quá thời gian làm bài quy định, bài nộp bị từ chối.');

        $this->quizService->submit($attempt->id, $student->id, [
            $questionIds[0] => 'A',
        ]);
    }

    public function test_bo_trong_cau_va_dap_an_khong_hop_le(): void
    {
        [$student, $courseId, $questionIds] = $this->seedQuizData(4);

        $started = $this->quizService->start($student->id, $courseId);
        $attemptId = $started['attempt']->id;

        $answers = [
            $questionIds[0] => 'A',
            $questionIds[2] => 'Z',
            $questionIds[3] => '',
        ];

        $result = $this->quizService->submit($attemptId, $student->id, $answers);

        $this->assertEquals(2.5, $result['score']);
        $this->assertEquals(1, $result['correct_count']);
    }
}