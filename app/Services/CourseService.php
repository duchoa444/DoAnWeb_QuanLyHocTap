<?php

namespace App\Services;

use App\Repositories\CourseRepository;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    // Lấy danh sách khóa học
    public function getCourses(int $teacherId)
    {
        return $this->courseRepository->getByTeacher($teacherId);
    }

    // Xem chi tiết khóa học
    public function getCourse(int $courseId, int $teacherId)
    {
        return $this->courseRepository->findByTeacher(
            $courseId,
            $teacherId
        );
    }

    // Thêm khóa học
    public function createCourse(array $data, int $teacherId)
    {
        $data['teacher_id'] = $teacherId;

        return $this->courseRepository->create($data);
    }

    // Cập nhật khóa học
    public function updateCourse(
        int $courseId,
        int $teacherId,
        array $data
    ) {
        $course = $this->courseRepository->findByTeacher(
            $courseId,
            $teacherId
        );

        return $this->courseRepository->update($course, $data);
    }

    // Xóa khóa học
    public function deleteCourse(int $courseId, int $teacherId)
    {
        $course = $this->courseRepository->findByTeacher(
            $courseId,
            $teacherId
        );

        return $this->courseRepository->delete($course);
    }
}
