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


    public function getAdminCoursesList(string $search = '', string $status = ''): LengthAwarePaginator
    {
        return $this->courseRepository->paginateForAdmin(trim($search), trim($status), 10);
    }

    public function approveCourse(Course $course): bool
    {
        return $this->courseRepository->updateStatus($course, 'published');
    }

    public function hideCourse(Course $course): bool
    {
        return $this->courseRepository->updateStatus($course, 'hidden');
    }
    public function getPendingCoursesCount(): int
    {
        return $this->courseRepository->countDraftCourses();
    }
}