@include('header')

<div style="max-width: 800px; margin: 30px auto; padding: 20px;">
    <h2 style="margin: 0 0 20px 0; color: #222;">Thêm khóa học mới</h2>

    @if ($errors->any())
        <div style="padding: 12px 16px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px; margin-bottom: 18px;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Thông tin khóa học --}}
        <div style="background: #fff; padding: 22px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">Tên khóa học <span style="color: red;">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 6px;">Danh mục <span style="color: red;">*</span></label>
                    <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 6px;">Trạng thái <span style="color: red;">*</span></label>
                    <select name="status" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Xuất bản</option>
                    </select>
                </div>
            </div>

            {{-- Ảnh bìa khóa học --}}
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">Ảnh bìa khóa học</label>
                <input type="file" name="thumbnail" accept="image/*" onchange="previewThumbnail(event)"
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; background: #fafafa;">
                <small style="color: #666; display: block; margin-top: 4px;">Định dạng hỗ trợ: JPG, PNG, GIF, WEBP (tối đa 2MB).</small>
                <div id="thumbnail-preview-wrap" style="display: none; margin-top: 10px;">
                    <img id="thumbnail-preview" src="" alt="Xem trước ảnh bìa"
                         style="max-width: 280px; max-height: 160px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">Mô tả</label>
                <textarea name="description" rows="4"
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">{{ old('description') }}</textarea>
            </div>

            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">Thời gian làm bài kiểm tra (phút) <span style="color: red;">*</span></label>
                <input type="number" name="quiz_minutes" value="{{ old('quiz_minutes', 30) }}" min="1" max="180" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
        </div>

        {{-- Danh sách bài học --}}
        <div style="background: #fff; padding: 22px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <h3 style="margin: 0; font-size: 17px;">Danh sách bài học</h3>
                <button type="button" onclick="addLessonRow()"
                        style="padding: 6px 12px; background: #17a2b8; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold;">
                    + Thêm bài học
                </button>
            </div>

            @php
                $oldLessons = old('lessons', [
                    ['title' => '', 'video_url' => '', 'content' => '']
                ]);
            @endphp

            <div id="lessons-container">
                @foreach ($oldLessons as $idx => $lesson)
                    <div class="lesson-item" style="padding: 14px; border: 1px solid #e5e5e5; border-radius: 6px; margin-bottom: 12px; background: #fafafa;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <strong style="font-size: 14px;">Bài học #{{ $idx + 1 }}</strong>
                            @if ($idx > 0)
                                <button type="button" onclick="this.closest('.lesson-item').remove()"
                                        style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 13px;">
                                    Xóa
                                </button>
                            @endif
                        </div>
                        <input type="text" name="lessons[{{ $idx }}][title]" value="{{ $lesson['title'] ?? '' }}"
                               placeholder="Tiêu đề bài học..."
                               style="width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px; box-sizing: border-box;">
                        <input type="text" name="lessons[{{ $idx }}][video_url]" value="{{ $lesson['video_url'] ?? '' }}"
                               placeholder="Link video bài giảng (tùy chọn)"
                               style="width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px; box-sizing: border-box;">
                        <textarea name="lessons[{{ $idx }}][content]" rows="2" placeholder="Nội dung bài học..."
                                  style="width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">{{ $lesson['content'] ?? '' }}</textarea>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Thêm khóa học
            </button>
            <a href="{{ route('teacher.courses.index') }}" style="padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
                Quay lại
            </a>
        </div>
    </form>
</div>

<script>
    function previewThumbnail(event) {
        const file = event.target.files[0];
        const wrap = document.getElementById('thumbnail-preview-wrap');
        const img = document.getElementById('thumbnail-preview');

        if (file) {
            img.src = URL.createObjectURL(file);
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
        }
    }

    let lessonIndex = {{ count($oldLessons) }};

    function addLessonRow() {
        const container = document.getElementById('lessons-container');
        const html = `
            <div class="lesson-item" style="padding: 14px; border: 1px solid #e5e5e5; border-radius: 6px; margin-bottom: 12px; background: #fafafa;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <strong style="font-size: 14px;">Bài học #${lessonIndex + 1}</strong>
                    <button type="button" onclick="this.closest('.lesson-item').remove()" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 13px;">Xóa</button>
                </div>
                <input type="text" name="lessons[${lessonIndex}][title]" placeholder="Tiêu đề bài học..."
                       style="width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px; box-sizing: border-box;">
                <input type="text" name="lessons[${lessonIndex}][video_url]" placeholder="Link video bài giảng (tùy chọn)"
                       style="width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 8px; box-sizing: border-box;">
                <textarea name="lessons[${lessonIndex}][content]" rows="2" placeholder="Nội dung bài học..."
                          style="width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"></textarea>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        lessonIndex++;
    }
</script>

@include('footer')