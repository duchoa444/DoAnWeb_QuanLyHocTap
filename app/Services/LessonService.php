<?php

namespace App\Services;

use App\Models\Lesson;
use App\Repositories\LessonRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LessonService
{
    public function __construct(
        protected LessonRepository $lessonRepository
    ) {}

    // Lấy danh sách bài học
    public function getTeacherLessonsList(
        int $courseId,
        int $teacherId
    ): LengthAwarePaginator {
        return $this->lessonRepository->paginateForTeacher(
            $courseId,
            $teacherId
        );
    }

    // Tìm bài học
    public function findTeacherLesson(
        int $lessonId,
        int $courseId,
        int $teacherId
    ): Lesson {
        return $this->lessonRepository->findForTeacherOrFail(
            $lessonId,
            $courseId,
            $teacherId
        );
    }

    // Tạo bài học
    public function createTeacherLesson(
        int $courseId,
        int $teacherId,
        array $data
    ): Lesson {
        return $this->lessonRepository->createForTeacher(
            $courseId,
            $teacherId,
            $data
        );
    }

    // Cập nhật bài học
    public function updateTeacherLesson(
        int $lessonId,
        int $courseId,
        int $teacherId,
        array $data
    ): Lesson {
        return $this->lessonRepository->updateForTeacher(
            $lessonId,
            $courseId,
            $teacherId,
            $data
        );
    }

    // Xóa bài học
    public function deleteTeacherLesson(
        int $lessonId,
        int $courseId,
        int $teacherId
    ): bool {
        return $this->lessonRepository->deleteForTeacher(
            $lessonId,
            $courseId,
            $teacherId
        );
    }

    // Di chuyển bài học lên hoặc xuống
    public function moveTeacherLesson(
        int $lessonId,
        int $courseId,
        int $teacherId,
        string $direction
    ): bool {
        return $this->lessonRepository->moveForTeacher(
            $lessonId,
            $courseId,
            $teacherId,
            $direction
        );
    }
}