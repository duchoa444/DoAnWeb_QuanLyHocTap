<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentService
{
    /**
     * Học viên ghi danh vào khóa học công khai (tối đa 1 lần/khóa)
     */
    public function enroll(User $student, Course $course): Enrollment
    {
        if (!in_array($course->status, ['published', 'public'])) {
            throw new Exception('Khóa học này chưa được công khai.');
        }

        $exists = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            throw new Exception('Bạn đã ghi danh khóa học này rồi.');
        }

        return Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);
    }

    /**
     * Lấy danh sách khóa học học viên đã ghi danh
     */
    public function getEnrolledCourses(User $student): Collection
    {
        return Course::whereHas('enrollments', function ($query) use ($student) {
            $query->where('user_id', $student->id);
        })->with('teacher')->latest()->get();
    }
}