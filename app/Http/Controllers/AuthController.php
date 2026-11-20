<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Đăng ký tài khoản
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8', // Thêm '|confirmed' nếu form có ô nhập lại mật khẩu
        ]);

        // Tài khoản đăng ký công khai mặc định là học viên
        $data['role'] = 'student';

        $user = $this->authService->register($data);

        // Nếu AuthService trả về đối tượng User thì tự động đăng nhập luôn
        if ($user instanceof Authenticatable) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('courses.index')
                ->with('success', 'Đăng ký tài khoản thành công!');
        }

        return redirect()->route('login')
            ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

// Đăng nhập
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($data);

        // Trường hợp đăng nhập thất bại (trả về false/null)
        if (!$result) {
            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])
                ->onlyInput('email');
        }

        // Trường hợp AuthService trả về chuỗi thông báo lỗi (ví dụ: tài khoản bị khóa) và chưa đăng nhập
        if (is_string($result) && !Auth::check()) {
            return back()
                ->withErrors(['email' => $result])
                ->onlyInput('email');
        }

        // Lấy đối tượng User đã đăng nhập từ Auth::user()
        $user = $result instanceof Authenticatable ? $result : Auth::user();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Không thể xác thực tài khoản. Vui lòng thử lại.'])
                ->onlyInput('email');
        }

        // Chặn nếu tài khoản đã bị Admin khóa
        if (isset($user->status) && in_array($user->status, ['locked', 'inactive', 'banned', 0, '0'], true)) {
            Auth::logout();

            return back()
                ->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Điều hướng đúng phân hệ theo vai trò (role)
        return match ($user->role) {
            'admin'   => redirect()->route('admin.courses.index')->with('success', 'Đăng nhập thành công!'),
            'teacher' => redirect()->route('teacher.courses.index')->with('success', 'Đăng nhập thành công!'),
            default   => redirect()->route('courses.index')->with('success', 'Đăng nhập thành công!'),
        };
    }
    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Bạn đã đăng xuất.');
    }
}