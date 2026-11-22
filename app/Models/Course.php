<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'teacher_id',
        'title',
        'description',
        'thumbnail',
        'quiz_minutes',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'course_id');
    }

    /**
     * Tính số bài hoàn thành, tổng số bài và % tiến độ cho học viên (HT-04)
     */
    public function getProgressStats(?int $userId): array
    {
        $totalLessons = $this->lessons_count ?? $this->lessons()->count();

        if (!$userId || $totalLessons === 0 || !Schema::hasTable('lesson_progress')) {
            return [
                'completed' => 0,
                'total' => $totalLessons,
                'percentage' => 0,
            ];
        }

        $lessonIds = $this->lessons()->pluck('id');

        $query = DB::table('lesson_progress')
            ->where('user_id', $userId)
            ->whereIn('lesson_id', $lessonIds);

        if (Schema::hasColumn('lesson_progress', 'is_completed')) {
            $query->where('is_completed', true);
        }

        $completedLessons = $query->distinct('lesson_id')->count('lesson_id');
        $percentage = (int) min(100, round(($completedLessons / $totalLessons) * 100));

        return [
            'completed' => $completedLessons,
            'total' => $totalLessons,
            'percentage' => $percentage,
        ];
    }
}