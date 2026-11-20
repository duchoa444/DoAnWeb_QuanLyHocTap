@include('header')

<div style="max-width: 700px; margin: 30px auto; padding: 20px;">
    <h2>Chỉnh sửa khóa học</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('teacher.courses.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label>Tên khóa học</label>
            <input type="text" name="title"
                   value="{{ old('title', $course->title) }}"
                   required style="width: 100%; padding: 10px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Danh mục</label>
            <select name="category_id" required style="width: 100%; padding: 10px;">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Mô tả</label>
            <textarea name="description" rows="4"
                      style="width: 100%; padding: 10px;">{{ old('description', $course->description) }}</textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Thời gian làm bài kiểm tra (phút)</label>
            <input type="number" name="quiz_minutes"
                   value="{{ old('quiz_minutes', $course->quiz_minutes) }}"
                   min="1" required style="width: 100%; padding: 10px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Trạng thái</label>
            <select name="status" required style="width: 100%; padding: 10px;">
                <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>
                    Bản nháp
                </option>
                <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>
                    Xuất bản
                </option>
            </select>
        </div>

        <button type="submit">Lưu thay đổi</button>
        <a href="{{ route('teacher.courses.index') }}">Quay lại</a>
    </form>
</div>

@include('footer')
