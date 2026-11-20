@include('header')

<div style="padding: 20px; max-width: 1150px; margin: 0 auto;">
    <h2 style="margin: 0 0 20px 0; color: #333;">Duyệt & Quản lý khóa học</h2>

    @if(session('success'))
        <div style="padding: 12px 16px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 6px; margin-bottom: 16px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 12px 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px; margin-bottom: 16px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background: #f1f3f5; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">#</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Tên khóa học</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Danh mục</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Giảng viên</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Trạng thái</th>
                    <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $index => $course)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;">{{ $courses->firstItem() + $index }}</td>
                        <td style="padding: 12px; font-weight: bold;">{{ $course->title }}</td>
                        <td style="padding: 12px;">{{ $course->category->name ?? 'Chưa phân loại' }}</td>
                        <td style="padding: 12px;">{{ $course->teacher->name ?? '—' }}</td>
                        <td style="padding: 12px;">
                            @if($course->status === 'pending')
                                <span style="padding: 3px 8px; background: #fff3cd; color: #856404; border-radius: 4px; font-size: 12px; font-weight: bold;">Chờ duyệt</span>
                            @elseif(in_array($course->status, ['published', 'public']))
                                <span style="padding: 3px 8px; background: #d4edda; color: #155724; border-radius: 4px; font-size: 12px; font-weight: bold;">Đã duyệt</span>
                            @else
                                <span style="padding: 3px 8px; background: #f8d7da; color: #721c24; border-radius: 4px; font-size: 12px; font-weight: bold;">Đã ẩn</span>
                            @endif
                        </td>
                        <td style="padding: 12px; display: flex; gap: 8px;">
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
                                <form action="{{ route('admin.courses.hide', $course->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="padding: 6px 12px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                                        Ẩn
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #777;">Chưa có khóa học nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $courses->links() }}
    </div>
</div>

@include('footer')