<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(
        protected CourseRepository $courseRepository
    ) {}

    // ==========================================
    // PHẦN 1: CÁC HÀM GIẢNG VIÊN CŨ
    // ==========================================

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

    // ==========================================
    // PHẦN 2: CHỨC NĂNG ADMIN
    // ==========================================

    public function getAdminCoursesList(
        string $search = '',
        string $status = ''
    ): LengthAwarePaginator {
        return $this->courseRepository->paginateForAdmin(
            trim($search),
            trim($status),
            10
        );
    }

    public function approveCourse(Course $course): bool
    {
        return $this->courseRepository->updateStatus(
            $course,
            'published'
        );
    }

    public function hideCourse(Course $course): bool
    {
        return $this->courseRepository->updateStatus(
            $course,
            'hidden'
        );
    }

    public function getPendingCoursesCount(): int
    {
        return $this->courseRepository->countDraftCourses();
    }

    // ==========================================
    // PHẦN 3: HT-09 - GIẢNG VIÊN
    // ==========================================

    // Danh sách khóa học của giảng viên
    public function getTeacherCoursesList(
        int $teacherId,
        string $search = ''
    ): LengthAwarePaginator {
        return $this->courseRepository->paginateForTeacher(
            $teacherId,
            trim($search),
            10
        );
    }

    // Tạo khóa học
    public function createTeacherCourse(
        int $teacherId,
        array $data
    ): Course {
        return $this->courseRepository->createForTeacher(
            $teacherId,
            $data
        );
    }

    // Tìm khóa học thuộc giảng viên
    public function findTeacherCourse(
        int $courseId,
        int $teacherId
    ): Course {
        return $this->courseRepository->findForTeacherOrFail(
            $courseId,
            $teacherId
        );
    }

    // Cập nhật khóa học
    public function updateTeacherCourse(
        int $courseId,
        int $teacherId,
        array $data
    ): Course {
        return $this->courseRepository->updateForTeacher(
            $courseId,
            $teacherId,
            $data
        );
    }

    // Xóa khóa học
    public function deleteTeacherCourse(
        int $courseId,
        int $teacherId
    ): bool {
        return $this->courseRepository->deleteForTeacher(
            $courseId,
            $teacherId
        );
    }
}
