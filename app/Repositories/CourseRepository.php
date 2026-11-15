<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    // Lấy danh sách khóa học của giảng viên
    public function getByTeacher(int $teacherId)
{
    return Course::where('teacher_id', $teacherId)
        ->with(['category', 'teacher'])
        ->withCount('lessons')
        ->latest()
        ->paginate(9);
    }

    // Tìm khóa học thuộc giảng viên
    public function findByTeacher(int $courseId, int $teacherId)
    {
        return Course::where('id', $courseId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();
    }

    // Thêm khóa học
    public function create(array $data)
    {
        return Course::create($data);
    }

    // Cập nhật khóa học
    public function update(Course $course, array $data)
    {
        $course->update($data);
        return $course->fresh();
    }

    // Xóa khóa học
    public function delete(Course $course)
    {
        return $course->delete();
    }
}
