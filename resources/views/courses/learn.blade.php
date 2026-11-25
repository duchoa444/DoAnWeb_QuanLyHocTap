@include('header')

@php
    // Tự động nhận diện tên cột video & PDF trong bảng lessons
    $rawVideo = $currentLesson->video_url ?? $currentLesson->video ?? $currentLesson->youtube_url ?? null;
    $embedUrl = $rawVideo;
    if ($rawVideo && preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $rawVideo, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
    }

    $pdfFile = $currentLesson->pdf_path ?? $currentLesson->file_path ?? $currentLesson->document_url ?? $currentLesson->attachment ?? null;
    $isCompleted = in_array($currentLesson->id, $completedLessonIds);
@endphp

<div style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">
    {{-- Thanh điều hướng trên cùng --}}
   <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;">
        <a href="{{ route('courses.show', $course->id) }}" style="color: #007bff; text-decoration: none; font-weight: bold;">
            &larr; Quay lại chi tiết khóa học: {{ $course->title }}
        </a>
        <span style="font-size: 14px; color: #555;">
            Tiến độ: <strong>{{ count($completedLessonIds) }}/{{ $lessons->count() }}</strong> bài hoàn thành
        </span>
    </div>



    {{-- Layout 2 cột: Danh sách bài bên trái (30%) - Nội dung bài bên phải (70%) --}}
    <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">

        {{-- CỘT TRÁI: Danh sách bài học --}}
        <div style="flex: 1; min-width: 280px; background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
            <div style="padding: 14px 16px; background: #f8f9fa; border-bottom: 1px solid #ddd; font-weight: bold;">
                Nội dung khóa học ({{ $lessons->count() }} bài)
            </div>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach($lessons as $index => $lesson)
                    @php
                        $active = $lesson->id === $currentLesson->id;
                        $done = in_array($lesson->id, $completedLessonIds);
                    @endphp
                    <li style="border-bottom: 1px solid #eee; background: {{ $active ? '#e8f4fd' : '#fff' }};">
                        <a href="{{ route('courses.learn', [$course->id, $lesson->id]) }}"
                           style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; text-decoration: none; color: {{ $active ? '#0056b3' : '#333' }}; font-weight: {{ $active ? 'bold' : 'normal' }};">
                            <span>Bài {{ $index + 1 }}: {{ $lesson->title }}</span>
                            @if($done)
                                <span style="color: #28a745; font-weight: bold; font-size: 13px;">&#10003; Xong</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- CỘT PHẢI: Nội dung bài học hiện tại --}}
     <div style="flex: 2.3; min-width: 300px; max-width: 100%; width: 100%; box-sizing: border-box; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 24px;">
            <h2 style="margin: 0 0 16px 0; font-size: 22px;">{{ $currentLesson->title }}</h2>

            {{-- 1. Video nhúng (nếu có) --}}
            @if($embedUrl)
                <div style="margin-bottom: 20px; background: #000; border-radius: 6px; overflow: hidden;">
                    @if(str_contains($embedUrl, 'youtube.com/embed') || str_contains($embedUrl, 'vimeo.com'))
                        <iframe src="{{ $embedUrl }}" style="width: 100%; aspect-ratio: 16 / 9; height: auto; border: none;" allowfullscreen></iframe>
                    @else
                        <video controls style="width: 100%; max-height: 400px;">
                            <source src="{{ asset($embedUrl) }}" type="video/mp4">
                            Trình duyệt của bạn không hỗ trợ phát video.
                        </video>
                    @endif
                </div>
            @endif

            {{-- 2. Nội dung văn bản bài học --}}
            <div style="line-height: 1.7; color: #333; margin-bottom: 20px; white-space: pre-line;">
                {{ $currentLesson->content ?? $currentLesson->description ?? 'Chưa có nội dung văn bản cho bài học này.' }}
            </div>

            {{-- 3. Liên kết tải tài liệu PDF (nếu có) --}}
            @if($pdfFile)
                <div style="padding: 12px 16px; background: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 24px;">
                    <strong>Tài liệu đính kèm:</strong>
                    <a href="{{ str_starts_with($pdfFile, 'http') ? $pdfFile : asset('storage/' . ltrim($pdfFile, '/')) }}"
                       target="_blank"
                       style="margin-left: 8px; color: #007bff; font-weight: bold; text-decoration: none;">
                        &#128196; Tải tài liệu PDF
                    </a>
                </div>
            @endif
            
            {{-- 4. Phần hỏi đáp (nếu có) --}}
            @if(isset($currentLesson) || isset($lesson))
                @include('courses.partials.lesson-qa', [
                    'lesson' => $currentLesson ?? $lesson,
                    'course' => $course,
                ])
            @endif
            {{-- 4. Thanh công cụ dưới: Bài trước | Đánh dấu hoàn thành | Bài sau --}}
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding-top: 16px; border-top: 1px solid #eee;">
                <div>
                    @if($prevLesson)
                        <a href="{{ route('courses.learn', [$course->id, $prevLesson->id]) }}"
                           style="display: inline-block; padding: 9px 16px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 4px;">
                            &larr; Bài trước
                        </a>
                    @endif
                </div>

                <div>
                    @if($isCompleted)
                        <button type="button" disabled
                                style="padding: 10px 20px; background: #28a745; color: #fff; border: none; border-radius: 4px; font-weight: bold; opacity: 0.85;">
                            &#10003; Đã hoàn thành
                        </button>
                    @else
                        <form method="POST" action="{{ route('lessons.complete', [$course->id, $currentLesson->id]) }}" style="margin: 0;">
                            @csrf
                            <button type="submit"
                                    style="padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                Đánh dấu hoàn thành
                            </button>
                        </form>
                    @endif
                </div>

                <div>
                    @if($nextLesson)
                        <a href="{{ route('courses.learn', [$course->id, $nextLesson->id]) }}"
                           style="display: inline-block; padding: 9px 16px; background: #17a2b8; color: #fff; text-decoration: none; border-radius: 4px;">
                            Bài sau &rarr;
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $totalLessons = $lessons->count();
    $completedCount = count($completedLessonIds ?? []);
    $canTakeQuiz = $totalLessons > 0 && $completedCount >= $totalLessons;
@endphp
<div style="max-width: 1200px; margin: 0 auto 24px auto; padding: 0 20px;">
@if($canTakeQuiz)
    <form action="{{ route('quiz.start', $course->id) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" style="padding: 10px 18px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Làm bài trắc nghiệm
        </button>
    </form>
@else
    <button type="button" disabled
            style="padding: 10px 18px; background: #ccc; color: #666; border: none; border-radius: 4px; cursor: not-allowed; font-weight: bold;">
        Làm bài trắc nghiệm (Hoàn thành {{ $completedCount }}/{{ $totalLessons }} bài để mở khóa)
    </button>
@endif
</div>
@include('footer')