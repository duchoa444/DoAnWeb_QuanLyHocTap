@php
    $thumb = $course->thumbnail ?? $course->image ?? null;
    $thumbUrl = ($thumb && file_exists(public_path($thumb))) ? asset($thumb) : null;
@endphp

<div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
    <div>
        <a href="{{ route('courses.show', $course->id) }}" style="display: block; text-decoration: none;">
            @if($thumbUrl)
                <img src="{{ $thumbUrl }}"
                     alt="{{ $course->title }}"
                     style="width: 100%; height: 165px; object-fit: cover; display: block; border-bottom: 1px solid #eee;">
            @else
                <div style="width: 100%; height: 165px; background: linear-gradient(135deg, #e0ecff 0%, #f3f6fb 100%); display: flex; align-items: center; justify-content: center; color: #5b7bb4; font-weight: 600; font-size: 14px; border-bottom: 1px solid #eee; padding: 0 16px; text-align: center;">
                    {{ $course->category->name ?? 'EduPlatform' }}
                </div>
            @endif
        </a>

        <div style="padding: 16px 18px 10px;">
            <h3 style="margin: 0 0 6px 0; font-size: 17px; color: #111827; line-height: 1.4;">
                {{ $course->title }}
            </h3>

            <p style="margin: 0 0 10px 0; font-size: 13.5px; color: #6b7280;">
                GV: {{ $course->teacher->name ?? 'Đang cập nhật' }}
            </p>

            <span style="display: inline-block; padding: 3px 9px; background: #f3f4f6; color: #374151; border-radius: 4px; font-size: 12.5px;">
                📚 {{ $course->lessons_count ?? $course->lessons->count() }} bài học
            </span>
        </div>
    </div>

    <div style="padding: 10px 18px 16px;">
        <a href="{{ route('courses.show', $course->id) }}"
           style="color: #007bff; text-decoration: none; font-weight: 600; font-size: 14px;">
            Xem chi tiết &rarr;
        </a>
    </div>
</div>