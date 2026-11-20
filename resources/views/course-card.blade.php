
<div style="border: 1px solid #ddd; width: 250px; border-radius: 8px; padding: 12px; margin: 10px; background: #fff;">

    @if(!empty($course->thumbnail))
    <img src="{{ asset($course->thumbnail) }}"
         alt="{{ $course->title }}"
         style="width: 100%; height: 180px; object-fit: cover; border-radius: 6px;">
    @endif

    <h3 style="font-size: 1.1rem; margin: 10px 0 5px;">
        {{ $course->title }}
    </h3>

    <p style="color: #666; font-size: 0.9rem; margin: 0 0 8px;">
        GV: {{ $course->teacher?->name ?? 'Chưa cập nhật' }}
    </p>

    <span style="background: #e9ecef; padding: 3px 8px; border-radius: 4px; font-size: 0.85rem;">
        📚 {{ $course->lessons_count }} bài học
    </span>

    <div style="margin-top: 10px;">
        <a href="{{ route('courses.show', $course->id) }}"
           style="color: #007bff; text-decoration: none; font-size: 0.9rem; font-weight: bold;">
            Xem chi tiết &rarr;
        </a>
    </div>
</div>
