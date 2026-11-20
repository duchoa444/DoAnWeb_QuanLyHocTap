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

@include('footer')