<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function getUsersList(string $search = ''): LengthAwarePaginator
    {
        return $this->userRepository->paginateWithSearch(trim($search), 10);
    }

    public function createTeacherAccount(array $data): User
    {
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'teacher',
            'is_active' => true,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function toggleUserStatus(User $targetUser, ?User $currentUser): bool
    {
        if ($targetUser->role === 'admin') {
            throw ValidationException::withMessages([
                'user' => 'Không thể khóa hoặc mở khóa tài khoản quản trị viên.',
            ]);
        }

        if ($currentUser !== null && $targetUser->is($currentUser)) {
            throw ValidationException::withMessages([
                'user' => 'Không thể tự khóa tài khoản đang đăng nhập.',
            ]);
        }

        $newStatus = !$targetUser->is_active;
        $this->userRepository->updateStatus($targetUser, $newStatus);

        return $newStatus;
    }
}