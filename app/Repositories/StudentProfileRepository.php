<?php

namespace App\Repositories;

use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudentProfileRepository
{
    public function updateUser(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function getQuizAttemptsByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return QuizAttempt::with(['course'])
            ->where('user_id', $userId)
            ->latest('submitted_at')
            ->paginate($perPage);
    }
}