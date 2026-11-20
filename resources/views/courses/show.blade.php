@include('header')

<div style="padding: 20px; max-width: 1000px; margin: 0 auto;">
    <a href="{{ route('courses.index') }}" style="display: inline-block; margin-bottom: 16px; color: #007bff; text-decoration: none;">
        &larr; Quay lại danh sách khóa học
    </a>
   
    <div style="background: #fff; padding: 24px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 24px;">
        <span style="display: inline-block; padding: 4px 10px; background: #e3f2fd; color: #1565c0; border-radius: 4px; font-size: 13px; margin-bottom: 10px;">
            {{ $course->category->name ?? 'Chưa phân loại' }}
        </span>

        <h1 style="margin: 0 0 12px 0; font-size: 26px;">{{ $course->title }}</h1>

        <p style="color: #555; margin-bottom: 16px; line-height: 1.6;">
            {{ $course->description ?? 'Chưa có mô tả cho khóa học này.' }}
        </p>

        <div style="display: flex; gap: 24px; flex-wrap: wrap; color: #444; font-size: 14px; margin-bottom: 20px; padding-top: 12px; border-top: 1px solid #eee;">
            <div><strong>Giảng viên:</strong> {{ $course->teacher->name ?? 'Đang cập nhật' }}</div>
            <div><strong>Học viên tham gia:</strong> {{ $enrollmentsCount ?? $course->enrollments_count ?? 0 }} học viên</div>
            <div><strong>Số bài học:</strong> {{ $course->lessons->count() }} bài</div>
        </div>

        {{-- Đã ghi danh -> Vào học | Chưa ghi danh -> Ghi danh (gọi HT-04) --}}
        @php
    $canLearn = $isEnrolled || (auth()->check() && (auth()->id() === $course->teacher_id || auth()->user()->role === 'admin'));
@endphp

@if($canLearn)
    <a href="{{ Route::has('courses.learn') ? route('courses.learn', $course->id) : '#' }}"
       style="display: inline-block; padding: 10px 22px; background: #28a745; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
        Vào học
    </a>
@else
   <form method="POST" action="{{ Route::has('courses.enroll') ? route('courses.enroll', $course->id) : '#' }}" style="display: inline;">
        @csrf
        <button type="submit"
                style="padding: 10px 22px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Ghi danh khóa học
        </button>
    </form>

    {{-- Nút bấm thử vào học ngay khi chưa ghi danh để test chặn quyền HT-NFR2 --}}
    <a href="{{ Route::has('courses.learn') ? route('courses.learn', $course->id) : '#' }}"
       style="display: inline-block; margin-left: 10px; padding: 10px 18px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 4px;">
        Vào học thử &rarr;
    </a>
@endif
    </div>

    {{-- Danh sách bài học (chỉ tiêu đề, chưa cho xem nội dung) --}}
    <div style="background: #fff; padding: 24px; border: 1px solid #ddd; border-radius: 8px;">
        <h3 style="margin: 0 0 16px 0;">Nội dung khóa học ({{ $course->lessons->count() }} bài học)</h3>

        @if($course->lessons->isEmpty())
            <p style="color: #666; margin: 0;">Khóa học này chưa có bài học nào.</p>
        @else
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($course->lessons as $index => $lesson)
                    <li style="padding: 12px 14px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                        <span><strong>Bài {{ $index + 1 }}:</strong> {{ $lesson->title }}</span>
                        <span style="font-size: 13px; color: #888;">Chưa mở khóa nội dung</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

@include('footer')