<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'teacher_id',
        'title',
        'description',
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
}