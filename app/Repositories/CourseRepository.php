<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CourseRepository
{
    // ==========================================
    // PHẦN 1: CHỨC NĂNG ADMIN
    // ==========================================

    // Lấy danh sách khóa học dành cho Admin
    public function paginateForAdmin(
        string $search = '',
        string $status = '',
        int $perPage = 10
    ): LengthAwarePaginator {
        return Course::query()
            ->with(['category', 'teacher'])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($status !== '', function (Builder $query) use ($status): void {
                if ($status === 'draft') {
                    $query->whereIn('status', ['draft', 'pending']);
                } elseif ($status === 'published') {
                    $query->whereIn('status', ['published', 'public']);
                } else {
                    $query->where('status', $status);
                }
            })
            ->orderByRaw(
                "FIELD(status, 'draft', 'pending', 'published', 'public', 'hidden')"
            )
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'courses_page')
            ->withQueryString();
    }

    // Đếm khóa học chờ duyệt
    public function countDraftCourses(): int
    {
        return Course::whereIn('status', ['draft', 'pending'])->count();
    }

    // Cập nhật trạng thái khóa học (Admin)
    public function updateStatus(Course $course, string $status): bool
    {
        $course->status = $status;

        return $course->save();
    }

    // ==========================================
    // PHẦN 2: CÁC HÀM GIẢNG VIÊN CŨ
    // ==========================================

    public function getByTeacher(int $teacherId)
    {
        return Course::where('teacher_id', $teacherId)
            ->with(['category', 'teacher'])
            ->withCount('lessons')
            ->latest()
            ->paginate(9);
    }

    public function findByTeacher(int $courseId, int $teacherId)
    {
        return Course::where('id', $courseId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data)
    {
        $course->update($data);

        return $course->fresh();
    }

    public function delete(Course $course)
    {
        return $course->delete();
    }

    // ==========================================
    // PHẦN 3: HT-09 - GIẢNG VIÊN QUẢN LÝ KHÓA HỌC
    // ==========================================

    // Lấy danh sách khóa học của giảng viên
    public function paginateForTeacher(
        int $teacherId,
        string $search = '',
        int $perPage = 10
    ): LengthAwarePaginator {
        return Course::query()
            ->with(['category', 'teacher'])
            ->withCount('lessons')
            ->where('teacher_id', $teacherId)
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    // Tìm khóa học thuộc giảng viên
    public function findForTeacherOrFail(
        int $courseId,
        int $teacherId
    ): Course {
        return Course::query()
            ->where('teacher_id', $teacherId)
            ->findOrFail($courseId);
    }

    // Tạo khóa học mới
    public function createForTeacher(
        int $teacherId,
        array $data
    ): Course {
        $data['teacher_id'] = $teacherId;

        return Course::create($data);
    }

    // Cập nhật khóa học
    public function updateForTeacher(
        int $courseId,
        int $teacherId,
        array $data
    ): Course {
        $course = $this->findForTeacherOrFail(
            $courseId,
            $teacherId
        );

        $course->update($data);

        return $course;
    }

    // Xóa khóa học
    public function deleteForTeacher(
        int $courseId,
        int $teacherId
    ): bool {
        $course = $this->findForTeacherOrFail(
            $courseId,
            $teacherId
        );

        return (bool) $course->delete();
    }
}
