<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LessonRepository
{
    // Lấy danh sách bài học thuộc khóa học của giảng viên
    public function paginateForTeacher(
        int $courseId,
        int $teacherId,
        int $perPage = 10
    ): LengthAwarePaginator {
        return Lesson::query()
            ->where('course_id', $courseId)
            ->whereHas('course', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orderBy('position')
            ->orderBy('id')
            ->paginate($perPage);
    }

    // Tìm bài học thuộc khóa học của giảng viên
    public function findForTeacherOrFail(
        int $lessonId,
        int $courseId,
        int $teacherId
    ): Lesson {
        return Lesson::query()
            ->where('course_id', $courseId)
            ->whereHas('course', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->findOrFail($lessonId);
    }

    // Tạo bài học mới
    public function createForTeacher(
        int $courseId,
        int $teacherId,
        array $data
    ): Lesson {
        Course::query()
            ->where('teacher_id', $teacherId)
            ->findOrFail($courseId);

        $data['course_id'] = $courseId;

        $data['position'] = (Lesson::where('course_id', $courseId)
            ->max('position') ?? 0) + 1;

        return Lesson::create($data);
    }

    // Cập nhật bài học
    public function updateForTeacher(
        int $lessonId,
        int $courseId,
        int $teacherId,
        array $data
    ): Lesson {
        $lesson = $this->findForTeacherOrFail(
            $lessonId,
            $courseId,
            $teacherId
        );

        $lesson->update($data);

        return $lesson->fresh();
    }

    // Xóa bài học
    public function deleteForTeacher(
        int $lessonId,
        int $courseId,
        int $teacherId
    ): bool {
        $lesson = $this->findForTeacherOrFail(
            $lessonId,
            $courseId,
            $teacherId
        );

        return (bool) $lesson->delete();
    }

    // Di chuyển bài học lên hoặc xuống
    public function moveForTeacher(
        int $lessonId,
        int $courseId,
        int $teacherId,
        string $direction
    ): bool {
        return DB::transaction(function () use (
            $lessonId,
            $courseId,
            $teacherId,
            $direction
        ) {
            $lesson = $this->findForTeacherOrFail(
                $lessonId,
                $courseId,
                $teacherId
            );

            $lessons = Lesson::query()
                ->where('course_id', $courseId)
                ->orderBy('position')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $currentIndex = $lessons->search(
                fn ($item) => $item->id === $lesson->id
            );

            if ($currentIndex === false) {
                abort(404);
            }

            $targetIndex = $direction === 'up'
                ? $currentIndex - 1
                : $currentIndex + 1;

            // Không di chuyển nếu đã ở đầu hoặc cuối danh sách
            if (!isset($lessons[$targetIndex])) {
                return false;
            }

            $targetLesson = $lessons[$targetIndex];

            $currentPosition = $lesson->position;

            $lesson->update([
                'position' => $targetLesson->position,
            ]);

            $targetLesson->update([
                'position' => $currentPosition,
            ]);

            return true;
        });
    }
}
