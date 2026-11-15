<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        // Kiểm tra người dùng đã đăng nhập chưa
        if (!$request->user()) {
            return redirect('/login');
        }

        if (!$request->user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')
                ->withErrors(['email' => 'Tài khoản đã bị khóa.']);
        }

        // Kiểm tra vai trò người dùng
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Bạn không có quyền truy cập chức năng này!');
        }

        // Cho phép tiếp tục nếu đúng quyền
        return $next($request);
    }
}
