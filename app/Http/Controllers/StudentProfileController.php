<?php

namespace App\Http\Controllers;

use App\Services\StudentProfileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function __construct(
        protected StudentProfileService $profileService
    ) {}

    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $attempts = $this->profileService->getExamHistory($user, 10);

        return view('student.profile', compact('user', 'attempts'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'name.required'  => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email'    => 'Địa chỉ email không đúng định dạng.',
            'email.unique'   => 'Email này đã được sử dụng bởi tài khoản khác.',
        ]);

        $this->profileService->updateProfile($user, $data);

        return redirect()->route('student.profile')
            ->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required'         => 'Vui lòng nhập mật khẩu mới.',
            'password.min'              => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed'        => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $this->profileService->changePassword(
            $user,
            $data['current_password'],
            $data['password']
        );

        return redirect()->route('student.profile')
            ->with('success', 'Đổi mật khẩu thành công!');
    }
}