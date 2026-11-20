<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\StudentProfileRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentProfileService
{
    public function __construct(
        protected StudentProfileRepository $profileRepository
    ) {}

    public function updateProfile(User $user, array $data): bool
    {
        return $this->profileRepository->updateUser($user, [
            'name'  => trim($data['name']),
            'email' => trim($data['email']),
        ]);
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mật khẩu hiện tại không chính xác.',
            ]);
        }

        return $this->profileRepository->updateUser($user, [
            'password' => Hash::make($newPassword),
        ]);
    }

    public function getExamHistory(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->profileRepository->getQuizAttemptsByUser($user->id, $perPage);
    }
}