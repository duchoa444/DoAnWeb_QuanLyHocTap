<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * HT-03: Đăng ký chỉ tạo tài khoản học viên (student)[cite: 19]
     */
    public function registerStudent(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']); // NFR-01: Băm bcrypt[cite: 11]
        $user->role = 'student'; // HT-03: Mặc định student[cite: 19]
        $user->is_active = true;
        $user->save();

        Auth::login($user);

        return $user;
    }

    /**
     * HT-03: Đăng nhập, chặn tài khoản khóa & điều hướng khớp với menu của TV3
     */
    public function loginAndGetRedirectUrl(array $credentials, bool $remember = false): string
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Email hoặc mật khẩu không chính xác.', // NFR-04[cite: 11]
            ]);
        }

        // Chặn tài khoản bị khóa (kiểm tra cả is_active hoặc status nếu TV4 dùng 1 trong 2)
        if ((isset($user->is_active) && !$user->is_active) || ($user->status ?? 'active') === 'locked') {
            throw ValidationException::withMessages([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ]);
        }

        Auth::login($user, $remember);

        // Điều hướng khớp chính xác với các route trong header.blade.php của TV3
        return match ($user->role) {
            'admin'   => '/admin/users',
            'teacher' => '/teacher/courses',
            default   => '/courses',
        };
    }
}