<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Question extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (Question $question) {
            $columns = Schema::getColumnListing($question->getTable());

            // 1. Tự động ánh xạ cột nội dung câu hỏi (content -> question_text / question / title)
            if (!in_array('content', $columns) && isset($question->attributes['content'])) {
                $val = $question->attributes['content'];
                unset($question->attributes['content']);

                foreach (['question_text', 'question', 'title'] as $col) {
                    if (in_array($col, $columns)) {
                        $question->attributes[$col] = $val;
                        break;
                    }
                }
            }

            // 2. Tự động ánh xạ cột đáp án đúng (correct_answer / correct_option / answer)
            foreach (['correct_answer', 'correct_option', 'answer'] as $src) {
                if (!in_array($src, $columns) && isset($question->attributes[$src])) {
                    $val = $question->attributes[$src];
                    unset($question->attributes[$src]);

                    foreach (['correct_answer', 'correct_option', 'answer'] as $target) {
                        if (in_array($target, $columns)) {
                            $question->attributes[$target] = $val;
                            break;
                        }
                    }
                }
            }
        });
    }

    public function getContentAttribute($value)
    {
        return $value
            ?? ($this->attributes['question_text'] ?? null)
            ?? ($this->attributes['question'] ?? null)
            ?? ($this->attributes['title'] ?? null);
    }

    public function getQuestionTextAttribute($value)
    {
        return $value ?? ($this->attributes['content'] ?? null);
    }

    public function getCorrectAnswerAttribute($value)
    {
        return $value
            ?? ($this->attributes['correct_option'] ?? null)
            ?? ($this->attributes['answer'] ?? null);
    }

    public function getCorrectOptionAttribute($value)
    {
        return $value ?? ($this->attributes['correct_answer'] ?? null);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}