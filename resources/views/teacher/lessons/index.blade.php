
@include('header')

<div style="max-width: 1200px; margin: 20px auto; padding: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;">
        <div>
            <h2 style="margin: 0 0 8px;">Quản lý bài học</h2>
            <p style="color: #666; margin: 0;">
                Khóa học: <strong>{{ $course->title }}</strong>
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('teacher.courses.index') }}"
               style="padding: 10px 18px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
                Quay lại khóa học
            </a>

            <a href="{{ route('teacher.lessons.create', $course->id) }}"
               style="padding: 10px 18px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                + Thêm bài học
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="padding: 12px; margin-bottom: 15px; background: #d1e7dd; color: #0f5132; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 750px;">
            <thead>
                <tr style="background: #f1f3f5;">
                    <th style="padding: 13px; text-align: center;">Thứ tự</th>
                    <th style="padding: 13px; text-align: left;">Tiêu đề bài học</th>
                    <th style="padding: 13px; text-align: center;">Nội dung</th>
                    <th style="padding: 13px; text-align: center;">Video</th>
                    <th style="padding: 13px; text-align: center;">Tệp PDF</th>
                    <th style="padding: 13px; text-align: center;">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @forelse($lessons as $lesson)
                    <tr style="border-top: 1px solid #eee;">
                        <td style="padding: 13px; text-align: center;">
                            {{ $lesson->position }}
                        </td>

                        <td style="padding: 13px;">
                            <strong>{{ $lesson->title }}</strong>
                        </td>

                        <td style="padding: 13px; text-align: center;">
                            {{ $lesson->content ? 'Có' : 'Không' }}
                        </td>

                        <td style="padding: 13px; text-align: center;">
                            @if($lesson->video_url)
                                <span style="color: #198754;">Có video</span>
                            @else
                                <span style="color: #999;">Không</span>
                            @endif
                        </td>

                        <td style="padding: 13px; text-align: center;">
                            @if($lesson->pdf_path)
                                <span style="color: #198754;">Đã tải lên</span>
                            @else
                                <span style="color: #999;">Không</span>
                            @endif
                        </td>

                        <td style="padding: 13px; text-align: center; white-space: nowrap;">
                            <a href="{{ route('teacher.lessons.edit', [$course->id, $lesson->id]) }}"
                               style="color: #007bff; text-decoration: none; margin-right: 8px;">
                                Sửa
                            </a>

                            <form action="{{ route('teacher.lessons.destroy', [$course->id, $lesson->id]) }}"
                                  method="POST"
                                  style="display: inline;"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        style="color: #dc3545; background: none; border: none; cursor: pointer;">
                                    Xóa
                                </button>
                            </form>

                            <div style="display: flex; justify-content: center; gap: 5px; margin-top: 8px;">
                                <form action="{{ route('teacher.lessons.move', [$course->id, $lesson->id]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="direction" value="up">
                                    <button type="submit" title="Di chuyển lên"
                                            style="padding: 4px 9px; cursor: pointer;">
                                        ↑
                                    </button>
                                </form>

                                <form action="{{ route('teacher.lessons.move', [$course->id, $lesson->id]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="direction" value="down">
                                    <button type="submit" title="Di chuyển xuống"
                                            style="padding: 4px 9px; cursor: pointer;">
                                        ↓
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #777;">
                            Khóa học này chưa có bài học nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $lessons->links() }}
    </div>

</div>

@include('footer')
