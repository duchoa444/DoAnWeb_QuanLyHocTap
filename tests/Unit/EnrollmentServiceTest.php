<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\Services\EnrollmentService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_enroll_in_published_course(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $category = Category::create(['name' => 'Lập trình Web']);

        $course = Course::create([
            'category_id' => $category->id,
            'teacher_id' => $teacher->id,
            'title' => 'Khóa học Laravel 12',
            'description' => 'Mô tả khóa học',
            'quiz_minutes' => 15,
            'status' => 'published',
        ]);

        $service = app(EnrollmentService::class);
        $service->enroll($student, $course);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_student_cannot_enroll_twice_in_same_course(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $category = Category::create(['name' => 'Lập trình Web']);

        $course = Course::create([
            'category_id' => $category->id,
            'teacher_id' => $teacher->id,
            'title' => 'Khóa học Laravel 12',
            'description' => 'Mô tả khóa học',
            'quiz_minutes' => 15,
            'status' => 'published',
        ]);

        $service = app(EnrollmentService::class);
        $service->enroll($student, $course);

        $this->expectException(Exception::class);
        $service->enroll($student, $course);
    }
}