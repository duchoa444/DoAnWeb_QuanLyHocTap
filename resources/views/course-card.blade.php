<div style="border: 1px solid #ddd; width: 250px; border-radius: 8px; padding: 12px; margin: 10px; background: #fff;">

    <img src="https://via.placeholder.com/250x150"
         alt="Ảnh bìa"
         style="width: 100%; border-radius: 4px;">

    <h3 style="font-size: 1.1rem; margin: 10px 0 5px;">
        {{ $course->title }}
    </h3>

    <p style="color: #666; font-size: 0.9rem;">
        GV: {{ $course->teacher?->name ?? 'Chưa cập nhật' }}
    </p>

    <span style="background: #e9ecef; padding: 3px 8px; border-radius: 4px;">
        📚 {{ $course->lessons_count }} bài học
    </span>

    <div style="margin-top: 12px;">
        <a href="{{ route('teacher.courses.show', $course->id) }}">Chi tiết</a>

        <a href="{{ route('teacher.courses.edit', $course->id) }}">Sửa</a>

        <form action="{{ route('teacher.courses.destroy', $course->id) }}"
              method="POST"
              onsubmit="return confirm('Bạn có chắc muốn xóa khóa học này?')"
              style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Xóa</button>
        </form>
    </div>
</div>