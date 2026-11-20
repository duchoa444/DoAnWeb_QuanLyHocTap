<?php

namespace App\Http\Controllers\Admin;
use App\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected CourseService $courseService
    ) {}
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $users = $this->userService->getUsersList($search);

        // Lấy tham số tìm kiếm & lọc trạng thái cho phần Duyệt khóa học
        $courseSearch = trim((string) $request->query('course_search', ''));
        $status = trim((string) $request->query('status', ''));

        $allowedStatuses = ['', 'draft', 'published', 'hidden'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        $courses = $this->courseService->getAdminCoursesList($courseSearch, $status);
        $pendingCoursesCount = $this->courseService->getPendingCoursesCount();

        return view('admin.users.index', compact(
            'users',
            'search',
            'courses',
            'pendingCoursesCount',
            'courseSearch',
            'status'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên giảng viên.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $this->userService->createTeacherAccount($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo tài khoản giảng viên thành công.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        $isActive = $this->userService->toggleUserStatus($user, $request->user());

        $message = $isActive
            ? 'Đã mở khóa tài khoản.'
            : 'Đã khóa tài khoản.';

        return back()->with('success', $message);
    }
}