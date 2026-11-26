@include('header')

<div style="max-width: 1200px; margin: 20px auto; padding: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;">
        <div>
            <h2 style="margin: 0 0 8px;">Quản lý khóa học</h2>
            <p style="color: #666; margin: 0;">Quản lý các khóa học, bài học và đề thi trắc nghiệm do bạn tạo.</p>
        </div>

        <a href="{{ route('teacher.courses.create') }}"
           style="padding: 10px 18px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
            + Thêm khóa học
        </a>
    </div>

    @if(session('error'))
        <div style="padding: 12px 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px; margin-bottom: 16px;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div style="padding: 12px 16px; background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; border-radius: 6px; margin-bottom: 16px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('teacher.courses.index') }}"
          style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 8px;">

        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Tìm kiếm tên khóa học..."
               style="flex: 1; min-width: 220px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">

        <button type="submit"
                style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Tìm kiếm
        </button>

        <a href="{{ route('teacher.courses.index') }}"
           style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
            Làm mới
        </a>
    </form>

    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 650px;">
            <thead>
                <tr style="background: #f1f3f5;">
                    <th style="padding: 13px; text-align: left;">STT</th>
                    <th style="padding: 13px; text-align: left;">Tên khóa học</th>
                    <th style="padding: 13px; text-align: left;">Danh mục</th>
                    <th style="padding: 13px; text-align: center;">Số bài học</th>
                    <th style="padding: 13px; text-align: center;">Trạng thái</th>
                    <th style="padding: 13px; text-align: center;">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @forelse($courses as $course)
                    <tr style="border-top: 1px solid #eee;">
                        <td style="padding: 13px;">
                            {{ $courses->firstItem() + $loop->index }}
                        </td>

                        <td style="padding: 13px;">
                            <strong>{{ $course->title }}</strong>
                        </td>

                        <td style="padding: 13px;">
                            {{ $course->category->name ?? 'Chưa có danh mục' }}
                        </td>

                        <td style="padding: 13px; text-align: center;">
                            {{ $course->lessons_count }}
                        </td>

                        <td style="padding: 13px; text-align: center;">
                            @if($course->status === 'public' || $course->status === 'published')
                                <span style="color: #198754; font-weight: 500;">Đã công khai</span>
                            @elseif($course->status === 'pending')
                                <span style="color: #0d6efd; font-weight: 500;">Chờ duyệt</span>
                            @elseif($course->status === 'hidden')
                                <span style="color: #dc3545; font-weight: 500;">Đã ẩn</span>
                            @else
                                <span style="color: #d97706; font-weight: 500;">Bản nháp</span>
                            @endif
                        </td>

                        <td style="padding: 13px; text-align: center; white-space: nowrap;">

                            <a href="{{ route('teacher.lessons.index', $course->id) }}"
                               style="color: #0d6efd; text-decoration: none; margin-right: 10px; font-weight: 600;">
                                Bài học
                            </a>

                            <a href="{{ route('teacher.questions.index', $course->id) }}"
                               style="color: #198754; text-decoration: none; margin-right: 10px; font-weight: 500;">
                                Đề thi
                            </a>

                            <a href="{{ route('teacher.courses.edit', $course->id) }}"
                               style="color: #007bff; text-decoration: none; margin-right: 10px;">
                                Sửa
                            </a>

                            <form action="{{ route('teacher.courses.destroy', $course->id) }}"
                                  method="POST" style="display: inline;"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        style="color: #dc3545; background: none; border: none; cursor: pointer; padding: 0;">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #777;">
                            Bạn chưa có khóa học nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $courses->links() }}
    </div>

</div>

@include('footer')
