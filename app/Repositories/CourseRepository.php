<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CourseRepository
{
    // ... Giữ nguyên các hàm của HT-01 (nếu có) ...

    public function paginateForAdmin(string $search = '', string $status = '', int $perPage = 10): LengthAwarePaginator
    {
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
            ->orderByRaw("FIELD(status, 'draft', 'pending', 'published', 'public', 'hidden')")
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'courses_page')
            ->withQueryString();
    }

    public function countDraftCourses(): int
    {
        return Course::whereIn('status', ['draft', 'pending'])->count();
    }

    public function updateStatus(Course $course, string $status): bool
    {
        $course->status = $status;
        return $course->save();
    }
}