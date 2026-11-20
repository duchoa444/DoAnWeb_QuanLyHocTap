@include('header')

<div style="max-width: 850px; margin: 30px auto; padding: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2 style="margin: 0 0 5px 0;">Chỉnh sửa khóa học</h2>
            <p style="color: #666; margin: 0;">Cập nhật thông tin khóa học và danh sách bài giảng của bạn.</p>
        </div>

        @if(Route::has('teacher.questions.index'))
            <a href="{{ route('teacher.questions.index', $course->id) }}"
               style="padding: 10px 16px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px;">
                Quản lý đề thi trắc nghiệm &rarr;
            </a>
        @endif
    </div>

    @if ($errors->any())
        <div style="padding: 12px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.courses.update', $course->id) }}"
          method="POST"
          enctype="multipart/form-data"
          style="background: white; padding: 25px; border: 1px solid #ddd; border-radius: 8px;">

        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Tên khóa học
            </label>

            <input type="text" name="title"
                   value="{{ old('title', $course->title) }}"
                   required maxlength="255"
                   style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                    Danh mục
                </label>

                <select name="category_id" required
                        style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                    Trạng thái
                </label>

                <select name="status"
                        style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    <option value="pending" {{ old('status', $course->status) === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Xuất bản</option>
                </select>
            </div>
        </div>

        {{-- Ảnh bìa khóa học --}}
        @php
            $currentThumb = $course->thumbnail ?? $course->image ?? null;
            $currentThumbUrl = ($currentThumb && file_exists(public_path($currentThumb))) ? asset($currentThumb) : null;
        @endphp

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Ảnh bìa khóa học
            </label>

            @if($currentThumbUrl)
                <div style="margin-bottom: 10px;">
                    <img src="{{ $currentThumbUrl }}" alt="{{ $course->title }}"
                         style="max-width: 280px; max-height: 160px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; display: block;">
                    <small style="color: #666;">Ảnh bìa hiện tại (chọn tệp mới bên dưới nếu muốn thay đổi).</small>
                </div>
            @endif

            <input type="file" name="thumbnail" accept="image/*"
                   style="width: 100%; box-sizing: border-box; padding: 9px; border: 1px solid #ccc; border-radius: 5px; background: #fafafa;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Mô tả khóa học
            </label>

            <textarea name="description" rows="4"
                      style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">{{ old('description', $course->description) }}</textarea>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Thời gian làm bài kiểm tra (phút)
            </label>

            <input type="number" name="quiz_minutes"
                   value="{{ old('quiz_minutes', $course->quiz_minutes) }}"
                   min="1" required
                   style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        {{-- PHẦN DANH SÁCH BÀI HỌC --}}
        @php
            $lessonsData = old('lessons');
            if (is_null($lessonsData)) {
                $existingLessons = $course->lessons ?? \Illuminate\Support\Facades\DB::table('lessons')->where('course_id', $course->id)->get();
                $lessonsData = [];
                foreach ($existingLessons as $item) {
                    $lessonsData[] = [
                        'id'        => $item->id,
                        'title'     => $item->title,
                        'video_url' => $item->video_url ?? $item->url ?? '',
                        'content'   => $item->content ?? $item->description ?? '',
                    ];
                }
            }
        @endphp

        <div style="margin-bottom: 25px; padding-top: 20px; border-top: 1px solid #eee;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <label style="font-weight: bold; font-size: 16px; margin: 0;">
                    Danh sách bài học
                </label>

                <button type="button" onclick="addLessonRow()"
                        style="padding: 8px 14px; background: #17a2b8; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 13px;">
                    + Thêm bài học
                </button>
            </div>

            <div id="lessons-container">
                @forelse($lessonsData as $idx => $lesson)
                    <div class="lesson-item" style="padding: 15px; border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 12px; background: #fafafa;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="font-size: 14px; color: #333;">Bài học #{{ $idx + 1 }}</strong>
                            <button type="button" onclick="removeLessonRow(this)"
                                    style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 13px; font-weight: bold;">
                                Xóa
                            </button>
                        </div>

                        @if(!empty($lesson['id']))
                            <input type="hidden" name="lessons[{{ $idx }}][id]" value="{{ $lesson['id'] }}">
                        @endif

                        <input type="text" name="lessons[{{ $idx }}][title]"
                               value="{{ $lesson['title'] ?? '' }}"
                               required placeholder="Tiêu đề bài học..."
                               style="width: 100%; box-sizing: border-box; padding: 9px 11px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px;">

                        <input type="text" name="lessons[{{ $idx }}][video_url]"
                               value="{{ $lesson['video_url'] ?? '' }}"
                               placeholder="Link video bài giảng (tùy chọn)"
                               style="width: 100%; box-sizing: border-box; padding: 9px 11px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px;">

                        <textarea name="lessons[{{ $idx }}][content]" rows="3"
                                  placeholder="Nội dung chi tiết bài học..."
                                  style="width: 100%; box-sizing: border-box; padding: 9px 11px; border: 1px solid #ccc; border-radius: 4px;">{{ $lesson['content'] ?? '' }}</textarea>
                    </div>
                @empty
                    <p id="empty-lesson-msg" style="color: #777; font-size: 14px; margin: 0 0 10px 0;">
                        Khóa học này chưa có bài học nào. Nhấn <strong>"+ Thêm bài học"</strong> để bắt đầu thêm.
                    </p>
                @endforelse
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit"
                    style="padding: 11px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                Lưu thay đổi
            </button>

            <a href="{{ route('teacher.courses.index') }}"
               style="padding: 11px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
                Quay lại
            </a>
        </div>

    </form>
</div>

<script>
    let lessonIndex = {{ count($lessonsData) }};

    function addLessonRow() {
        const emptyMsg = document.getElementById('empty-lesson-msg');
        if (emptyMsg) {
            emptyMsg.remove();
        }

        const container = document.getElementById('lessons-container');
        const currentCount = container.querySelectorAll('.lesson-item').length + 1;

        const html = `
            <div class="lesson-item" style="padding: 15px; border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 12px; background: #fafafa;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <strong style="font-size: 14px; color: #333;">Bài học #${currentCount}</strong>
                    <button type="button" onclick="removeLessonRow(this)"
                            style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 13px; font-weight: bold;">
                        Xóa
                    </button>
                </div>

                <input type="text" name="lessons[${lessonIndex}][title]"
                       required placeholder="Tiêu đề bài học..."
                       style="width: 100%; box-sizing: border-box; padding: 9px 11px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px;">

                <input type="text" name="lessons[${lessonIndex}][video_url]"
                       placeholder="Link video bài giảng (tùy chọn)"
                       style="width: 100%; box-sizing: border-box; padding: 9px 11px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px;">

                <textarea name="lessons[${lessonIndex}][content]" rows="3"
                          placeholder="Nội dung chi tiết bài học..."
                          style="width: 100%; box-sizing: border-box; padding: 9px 11px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
        lessonIndex++;
    }

    function removeLessonRow(button) {
        button.closest('.lesson-item').remove();
    }
</script>

@include('footer')