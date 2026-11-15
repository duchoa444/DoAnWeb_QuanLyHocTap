@include('header')

<div style="max-width: 700px; margin: 30px auto; padding: 20px;">
    <h2>{{ $course->title }}</h2>

    <p><strong>Giảng viên:</strong> {{ $course->teacher?->name ?? 'Chưa cập nhật' }}</p>
    <p><strong>Danh mục:</strong> {{ $course->category?->name ?? 'Chưa phân loại' }}</p>
    <p><strong>Mô tả:</strong> {{ $course->description ?? 'Chưa có mô tả' }}</p>
    <p><strong>Thời gian kiểm tra:</strong> {{ $course->quiz_minutes }} phút</p>
    <p><strong>Trạng thái:</strong> {{ $course->status }}</p>

    <a href="{{ route('teacher.courses.edit', $course->id) }}">Chỉnh sửa</a>
    <a href="{{ route('teacher.courses.index') }}">Quay lại danh sách</a>
</div>

@include('footer')
