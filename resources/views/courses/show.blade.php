@include('header')

<div style="padding: 20px; max-width: 1000px; margin: 0 auto;">
    <a href="{{ route('courses.index') }}" style="display: inline-block; margin-bottom: 16px; color: #007bff; text-decoration: none;">
        &larr; Quay lại danh sách khóa học
    </a>

    @php
        $thumb = $course->thumbnail ?? $course->image ?? null;
        $thumbUrl = ($thumb && file_exists(public_path($thumb))) ? asset($thumb) : null;
        $user = auth()->user();
        $canViewLessons = $isEnrolled
            || ($user && $user->role === 'admin')
            || ($user && $user->role === 'teacher' && $course->teacher_id === $user->id);
    @endphp

    <div style="background: #fff; padding: 24px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 24px;">
        @if($thumbUrl)
            <div style="margin-bottom: 18px;">
                <img src="{{ $thumbUrl }}"
                     alt="{{ $course->title }}"
                     style="width: 100%; max-height: 340px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; display: block;">
            </div>
        @endif

        <span style="display: inline-block; padding: 4px 10px; background: #e3f2fd; color: #1565c0; border-radius: 4px; font-size: 13px; margin-bottom: 10px;">
            {{ $course->category->name ?? 'Chưa phân loại' }}
        </span>

        <h1 style="margin: 0 0 12px 0; font-size: 26px;">{{ $course->title }}</h1>

        <p style="color: #555; margin-bottom: 16px; line-height: 1.6;">
            {{ $course->description ?? 'Chưa có mô tả cho khóa học này.' }}
        </p>

        <div style="display: flex; gap: 24px; flex-wrap: wrap; color: #444; font-size: 14px; margin-bottom: 20px; padding-top: 12px; border-top: 1px solid #eee;">
            <div><strong>Giảng viên:</strong> {{ $course->teacher->name ?? 'Đang cập nhật' }}</div>
            <div><strong>Học viên tham gia:</strong> {{ $course->enrollments_count ?? 0 }} học viên</div>
            <div><strong>Số bài học:</strong> {{ $course->lessons->count() }} bài</div>
            <div><strong>Thời gian kiểm tra:</strong> {{ $course->quiz_minutes ?? 30 }} phút</div>
        </div>

        @auth
            @if($user->role === 'student')
                {{-- 1. HỌC VIÊN: Giữ nguyên luồng Ghi danh / Vào học --}}
                @if($isEnrolled)
                    @if($course->lessons->isEmpty())
                        <button type="button" disabled
                                style="padding: 10px 22px; background: #ccc; color: #666; border: none; border-radius: 4px; cursor: not-allowed; font-weight: bold;">
                            Giảng viên đang cập nhật bài học
                        </button>
                    @else
                        <a href="{{ route('courses.learn', $course->id) }}"
                           style="display: inline-block; padding: 10px 22px; background: #28a745; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
                            Vào học
                        </a>
                    @endif
                @else
                    <form method="POST" action="{{ route('courses.enroll', $course->id) }}" style="display: inline;">
                        @csrf
                        <button type="submit"
                                style="padding: 10px 22px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                            Ghi danh khóa học
                        </button>
                    </form>
                @endif

            @elseif($user->role === 'teacher')
                {{-- 2. GIẢNG VIÊN: Chỉ khóa học của chính mình mới có nút Xem bài học --}}
                @if($course->teacher_id === $user->id)
                    @if($course->lessons->isEmpty())
                        <button type="button" disabled
                                style="padding: 10px 22px; background: #ccc; color: #666; border: none; border-radius: 4px; cursor: not-allowed; font-weight: bold;">
                            Khóa học chưa có bài học
                        </button>
                    @else
                        <a href="{{ route('courses.learn', $course->id) }}"
                           style="display: inline-block; padding: 10px 22px; background: #17a2b8; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
                            Xem bài học của tôi
                        </a>
                    @endif
                @else
                    <span style="display: inline-block; padding: 10px 18px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px;">
                        Khóa học thuộc giảng viên khác (chế độ xem thông tin cơ bản)
                    </span>
                @endif

            @elseif($user->role === 'admin')
                {{-- 3. QUẢN TRỊ VIÊN: Xem bài học + Nút Ẩn/Hiện khóa học (đã xóa thẻ form lặp) --}}
                @if(!$course->lessons->isEmpty())
                    <a href="{{ route('courses.learn', $course->id) }}"
                       style="display: inline-block; padding: 10px 22px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 8px;">
                        Xem bài học
                    </a>
                @endif

                @php
                    $isVisible = in_array($course->status, ['published', 'public']);
                @endphp

                <form method="POST"
                      action="{{ $isVisible ? route('admin.courses.hide', $course->id) : route('admin.courses.approve', $course->id) }}"
                      style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            onclick="return confirm('Bạn có chắc muốn {{ $isVisible ? 'ẩn' : 'duyệt / hiện' }} khóa học này không?')"
                            style="padding: 10px 22px; background: {{ $isVisible ? '#dc3545' : '#28a745' }}; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        {{ $isVisible ? 'Ẩn khóa học' : 'Duyệt / Hiện khóa học' }}
                    </button>
                </form>
            @endif
        @else
            {{-- 4. KHÁCH CHƯA ĐĂNG NHẬP --}}
            <a href="{{ route('login') }}"
               style="display: inline-block; padding: 10px 22px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
                Đăng nhập để ghi danh
            </a>
        @endauth
    </div>

    {{-- Danh sách bài học --}}
    <div style="background: #fff; padding: 24px; border: 1px solid #ddd; border-radius: 8px;">
        <h3 style="margin: 0 0 16px 0;">Nội dung khóa học ({{ $course->lessons->count() }} bài học)</h3>

        @if($course->lessons->isEmpty())
            <p style="color: #666; margin: 0;">Khóa học này chưa có bài học nào.</p>
        @else
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($course->lessons as $index => $lesson)
                    <li style="padding: 12px 14px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                        <span><strong>Bài {{ $index + 1 }}:</strong> {{ $lesson->title }}</span>
                        <span style="font-size: 13px; color: {{ $canViewLessons ? '#28a745' : '#888' }};">
                            {{ $canViewLessons ? 'Đã mở khóa' : 'Chưa mở khóa nội dung' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

@include('footer')      