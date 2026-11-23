<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LessonCompletionTest extends TestCase
{
    use RefreshDatabase;

    private function createSampleCourseAndLesson(): array
    {
        $teacher = User::factory()->create(['role' => 'instructor']);

        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Lập trình',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course = Course::create([
            'title' => 'Khóa học Test',
            'description' => 'Mô tả khóa học',
            'teacher_id' => $teacher->id,
            'category_id' => $categoryId,
            'status' => 'published',
        ]);

       $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Bài học số 1',
            'content' => 'Nội dung bài học',
            'position' => 1,
        ]);

        return [$course, $lesson];
    }

    /**
     * Kịch bản 1: Đánh dấu hoàn thành 2 lần cùng 1 bài học không tạo bản ghi trùng
     */
    public function test_mark_lesson_completed_twice_does_not_create_duplicate_record(): void
    {
        [$course, $lesson] = $this->createSampleCourseAndLesson();
        $student = User::factory()->create(['role' => 'student']);

        DB::table('enrollments')->insert([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($student)->post(route('lessons.complete', [$course->id, $lesson->id]));
        $this->actingAs($student)->post(route('lessons.complete', [$course->id, $lesson->id]));

        $this->assertDatabaseCount('lesson_progress', 1);
    }

    /**
     * Kịch bản 2: Học viên chưa ghi danh khóa học thì bị từ chối khi vào học
     */
    public function test_unenrolled_student_cannot_access_lesson(): void
    {
        [$course, $lesson] = $this->createSampleCourseAndLesson();
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get(route('courses.learn', [$course->id, $lesson->id]));

        $this->assertTrue(in_array($response->status(), [302, 403]));
    }
}