@include('header')

<main style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">
    <h2>Quản lý người dùng</h2>

    @if (session('success'))
        <div role="status" style="padding: 12px; margin-bottom: 16px; background: #e8f5e9; color: #2e7d32; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" style="padding: 12px; margin-bottom: 16px; background: #ffebee; color: #c62828; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
        <h3 style="margin-top: 0;">Tạo tài khoản giảng viên</h3>
        <form action="{{ route('admin.users.store') }}" method="POST" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: end;">
            @csrf
            <label style="flex: 1 1 180px;">
                Họ và tên
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                       style="display: block; width: 100%; box-sizing: border-box; padding: 9px; margin-top: 5px;">
            </label>
            <label style="flex: 1 1 220px;">
                Email
                <input type="email" name="email" value="{{ old('email') }}" required
                       style="display: block; width: 100%; box-sizing: border-box; padding: 9px; margin-top: 5px;">
            </label>
            <label style="flex: 1 1 160px;">
                Mật khẩu
                <input type="password" name="password" required minlength="8"
                       style="display: block; width: 100%; box-sizing: border-box; padding: 9px; margin-top: 5px;">
            </label>
            <label style="flex: 1 1 160px;">
                Xác nhận mật khẩu
                <input type="password" name="password_confirmation" required minlength="8"
                       style="display: block; width: 100%; box-sizing: border-box; padding: 9px; margin-top: 5px;">
            </label>
            <button type="submit" style="padding: 10px 16px; border: 0; border-radius: 4px; background: #007bff; color: #fff; cursor: pointer;">
                Tạo tài khoản
            </button>
        </form>
    </section>

    <section style="padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
        <h3 style="margin-top: 0;">Danh sách tài khoản</h3>
        <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 8px; margin-bottom: 16px;">
            <input type="search" name="search" value="{{ $search }}" placeholder="Tìm theo tên hoặc email"
                   style="flex: 1; padding: 9px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 9px 16px;">Tìm kiếm</button>
            @if ($search !== '')
                <a href="{{ route('admin.users.index') }}" style="padding: 9px;">Xóa tìm kiếm</a>
            @endif
        </form>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <th style="padding: 10px;">Họ và tên</th>
                        <th style="padding: 10px;">Email</th>
                        <th style="padding: 10px;">Vai trò</th>
                        <th style="padding: 10px;">Trạng thái</th>
                        <th style="padding: 10px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;">{{ $user->name }}</td>
                            <td style="padding: 10px;">{{ $user->email }}</td>
                            <td style="padding: 10px;">
                                {{ ['admin' => 'Quản trị viên', 'teacher' => 'Giảng viên', 'student' => 'Học viên'][$user->role] ?? $user->role }}
                            </td>
                            <td style="padding: 10px;">{{ $user->is_active ? 'Đang hoạt động' : 'Đã khóa' }}</td>
                            <td style="padding: 10px;">
                                @if ($user->role !== 'admin' && $user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" style="padding: 6px 10px; border: 0; border-radius: 4px; background: {{ $user->is_active ? '#dc3545' : '#28a745' }}; color: #fff; cursor: pointer;">
                                            {{ $user->is_active ? 'Khóa' : 'Mở khóa' }}
                                        </button>
                                    </form>
                                @else
                                    <span>Không thể khóa</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 16px; text-align: center;">Không tìm thấy người dùng.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $users->links() }}
        </div>
    </section>
</main>
{{-- ================= PHẦN DUYỆT KHÓA HỌC ĐỒNG BỘ KHUÔN TRÊN ================= --}}
<section id="courses-section" style="margin-top: 24px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
        <h3 style="margin: 0;">Duyệt khóa học</h3>
        <span style="font-size: 14px; color: #666;">{{ $pendingCoursesCount }} khóa học chờ duyệt</span>
    </div>

    <form action="{{ route('admin.users.index') }}#courses-section" method="GET" style="display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap;">
        @if ($search !== '')
            <input type="hidden" name="search" value="{{ $search }}">
        @endif

        <input type="search" name="course_search" value="{{ $courseSearch }}" placeholder="Tìm theo tên khóa học"
               style="flex: 1; min-width: 200px; padding: 9px; border: 1px solid #ccc; border-radius: 4px;">

        <select name="status" style="padding: 9px; border: 1px solid #ccc; border-radius: 4px; background: #fff;">
            <option value="" {{ $status === '' ? 'selected' : '' }}>Tất cả trạng thái</option>
            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Chờ duyệt</option>
            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Đã duyệt</option>
            <option value="hidden" {{ $status === 'hidden' ? 'selected' : '' }}>Đã ẩn vi phạm</option>
        </select>

        <button type="submit" style="padding: 9px 16px;">Tìm kiếm</button>

        @if ($courseSearch !== '' || $status !== '')
            <a href="{{ route('admin.users.index', $search !== '' ? ['search' => $search] : []) }}#courses-section" style="padding: 9px;">Xóa tìm kiếm</a>
        @endif
    </form>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #ddd;">
                    <th style="padding: 10px;">Tên khóa học</th>
                    <th style="padding: 10px;">Giảng viên</th>
                    <th style="padding: 10px;">Ngày gửi</th>
                    <th style="padding: 10px;">Trạng thái</th>
                    <th style="padding: 10px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                    @forelse ($courses as $course)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;">{{ $course->title }}</td>
                            <td style="padding: 10px;">{{ $course->teacher?->name ?? 'N/A' }}</td>
                            <td style="padding: 10px;">{{ $course->created_at?->format('d/m/Y') }}</td>
                            <td style="padding: 10px;">
                                {{ ['draft' => 'Chờ duyệt', 'pending' => 'Chờ duyệt', 'published' => 'Đã duyệt', 'public' => 'Đã duyệt', 'hidden' => 'Đã ẩn vi phạm'][$course->status] ?? $course->status }}
                            </td>
                            <td style="padding: 10px;">
                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                    <button type="button"
                                            data-title="{{ $course->title }}"
                                            data-teacher="{{ $course->teacher?->name ?? 'N/A' }}"
                                            data-category="{{ $course->category?->name ?? 'Chưa phân loại' }}"
                                            data-description="{{ $course->description ?? 'Không có mô tả' }}"
                                            onclick="openCoursePreview(this)"
                                            style="padding: 6px 10px; border: 0; border-radius: 4px; background: #6c757d; color: #fff; cursor: pointer;">
                                        Xem
                                    </button>

                                    @if (!in_array($course->status, ['published', 'public'], true))
                                        <form action="{{ route('admin.courses.approve', $course) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" style="padding: 6px 10px; border: 0; border-radius: 4px; background: #28a745; color: #fff; cursor: pointer;">
                                                Duyệt
                                            </button>
                                        </form>
                                    @endif

                                    @if ($course->status !== 'hidden')
                                        <form action="{{ route('admin.courses.hide', $course) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirm('Xác nhận từ chối / ẩn khóa học vi phạm này?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" style="padding: 6px 10px; border: 0; border-radius: 4px; background: #dc3545; color: #fff; cursor: pointer;">
                                                Từ chối
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 16px; text-align: center;">Không tìm thấy khóa học.</td>
                        </tr>
                    @endforelse
                </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
@include('footer')