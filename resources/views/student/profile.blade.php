@include('header')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Khối 1: Cập nhật thông tin & Đổi mật khẩu --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px;">

        {{-- Form sửa thông tin cá nhân --}}
        <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
            <h2 style="margin-top: 0; font-size: 18px; color: #007bff; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                Thông tin cá nhân
            </h2>

            <form action="{{ route('student.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px;">Họ và tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                    @error('name')
                        <span style="color: #dc3545; font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px;">Email đăng nhập</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                    @error('email')
                        <span style="color: #dc3545; font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px;">Vai trò</label>
                    <input type="text" value="Học viên (Student)" disabled
                           style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #e0e0e0; border-radius: 4px; background: #f1f3f5; color: #666;">
                </div>

                <button type="submit" style="padding: 9px 18px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    Lưu thay đổi
                </button>
            </form>
        </div>

        {{-- Form đổi mật khẩu --}}
        <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
            <h2 style="margin-top: 0; font-size: 18px; color: #007bff; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                Đổi mật khẩu
            </h2>

            <form action="{{ route('student.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px;">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" required
                           style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                    @error('current_password')
                        <span style="color: #dc3545; font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px;">Mật khẩu mới (tối thiểu 8 ký tự)</label>
                    <input type="password" name="password" required
                           style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                    @error('password')
                        <span style="color: #dc3545; font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px;">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" required
                           style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <button type="submit" style="padding: 9px 18px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    Cập nhật mật khẩu
                </button>
            </form>
        </div>
    </div>

    {{-- Khối 2: Lịch sử làm bài trắc nghiệm & Điểm số --}}
    <div id="exam-history" style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
        <h2 style="margin-top: 0; font-size: 18px; color: #007bff; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            Lịch sử các lần làm bài & Điểm số
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: #f1f3f5; text-align: left;">
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">#</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Khóa học</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Bài trắc nghiệm</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Điểm số</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Đánh giá</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Thời gian nộp</th>
                    </tr>
                </thead>
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
    <thead>
        <tr style="background: #f1f3f5; text-align: left;">
            <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">#</th>
            <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Khóa học</th>
            <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Bắt đầu làm</th>
            <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Thời gian nộp</th>
            <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Điểm số</th>
            <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Đánh giá</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attempts as $index => $attempt)
            @php
                $score = $attempt->score ?? 0;
                $isPassed = $score >= 5;
            @endphp
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px 12px;">{{ $attempts->firstItem() + $index }}</td>
                <td style="padding: 10px 12px; font-weight: bold;">
                    {{ $attempt->course->title ?? 'Khóa học #' . $attempt->course_id }}
                </td>
                <td style="padding: 10px 12px; color: #666;">
                    {{ $attempt->started_at?->format('d/m/Y H:i') ?? '—' }}
                </td>
                <td style="padding: 10px 12px; color: #666;">
                    {{ ($attempt->submitted_at ?? $attempt->created_at)?->format('d/m/Y H:i') }}
                </td>
                <td style="padding: 10px 12px; font-weight: bold; color: #007bff;">
                    {{ number_format($score, 2) }} / 10
                </td>
                <td style="padding: 10px 12px;">
                    @if($isPassed)
                        <span style="padding: 3px 8px; background: #d4edda; color: #155724; border-radius: 4px; font-size: 12px; font-weight: bold;">
                            Đạt
                        </span>
                    @else
                        <span style="padding: 3px 8px; background: #f8d7da; color: #721c24; border-radius: 4px; font-size: 12px; font-weight: bold;">
                            Chưa đạt
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding: 20px; text-align: center; color: #777;">
                    Bạn chưa thực hiện bài kiểm tra trắc nghiệm nào.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
            </table>
        </div>

        <div style="margin-top: 15px;">
            {{ $attempts->links() }}
        </div>
    </div>

</div>
@include('footer')