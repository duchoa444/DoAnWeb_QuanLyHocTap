@include('header')

<div style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <h2 style="margin: 0; color: #333;">Duyệt & Quản lý khóa học</h2>
        <a href="{{ url('/admin/users') }}" style="padding: 8px 14px; border: 1px solid #007bff; color: #007bff; text-decoration: none; border-radius: 4px; font-size: 14px;">
            &larr; Quay lại Quản lý tài khoản
        </a>
    </div>

    

    @if(session('error'))
        <div style="padding: 12px 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px; margin-bottom: 16px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Thanh tìm kiếm & lọc trạng thái khớp với Admin\CourseController --}}
    <form method="GET" action="{{ url()->current() }}" style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
        <input type="text"
               name="search"
               value="{{ $search }}"
               placeholder="Tìm theo tên khóa học hoặc giảng viên..."
               style="flex: 1; min-width: 240px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">

        <select name="status" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; min-width: 180px;">
            <option value="" {{ $status === '' ? 'selected' : '' }}>-- Tất cả trạng thái --</option>
            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Đã duyệt (Công khai)</option>
            <option value="hidden" {{ $status === 'hidden' ? 'selected' : '' }}>Đã ẩn (Vi phạm)</option>
        </select>

        <button type="submit" style="padding: 8px 18px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Lọc dữ liệu
        </button>

        @if($search !== '' || $status !== '')
            <a href="{{ url()->current() }}" style="padding: 8px 14px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 4px;">
                Xóa lọc
            </a>
        @endif
    </form>

    {{-- Bảng danh sách khóa học --}}
    <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background: #f1f3f5; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">ID</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Tên khóa học</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Danh mục</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Giảng viên</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Trạng thái</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">#{{ $course->id }}</td>
                        <td style="padding: 12px;">
                            <strong>{{ $course->title }}</strong>
                            @if(!empty($course->description))
                                <div style="font-size: 12px; color: #666; margin-top: 4px;">
                                    {{ \Illuminate\Support\Str::limit($course->description, 80) }}
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px;">{{ $course->category->name ?? 'Chưa phân loại' }}</td>
                        <td style="padding: 12px;">{{ $course->teacher->name ?? '—' }}</td>
                        <td style="padding: 12px;">
                            @if($course->status === 'pending')
                                <span style="padding: 4px 8px; background: #fff3cd; color: #856404; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    Chờ duyệt
                                </span>
                            @elseif(in_array($course->status, ['published', 'public']))
                                <span style="padding: 4px 8px; background: #d4edda; color: #155724; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    Đã duyệt
                                </span>
                            @elseif($course->status === 'hidden')
                                <span style="padding: 4px 8px; background: #f8d7da; color: #721c24; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    Đã ẩn
                                </span>
                            @else
                                <span style="padding: 4px 8px; background: #e2e3e5; color: #383d41; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    Bản nháp
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                @if(!in_array($course->status, ['published', 'public']))
                                    <form action="{{ route('admin.courses.approve', $course->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" style="padding: 6px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                            Duyệt
                                        </button>
                                    </form>
                                @endif

                                @if($course->status !== 'hidden')
                                    <form action="{{ route('admin.courses.hide', $course->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Bạn có chắc muốn ẩn khóa học này khỏi trang công khai?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" style="padding: 6px 12px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                                            Ẩn
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 24px; text-align: center; color: #777;">
                            Không tìm thấy khóa học nào phù hợp.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $courses->withQueryString()->links() }}
    </div>
</div>

@include('footer')