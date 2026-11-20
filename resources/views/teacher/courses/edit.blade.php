
@include('header')

<div style="max-width: 850px; margin: 30px auto; padding: 20px;">

    <h2>Chỉnh sửa khóa học</h2>
    <p style="color: #666;">Cập nhật thông tin khóa học của bạn.</p>

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

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Danh mục
            </label>

            <select name="category_id" required
                    style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Mô tả khóa học
            </label>

            <textarea name="description" rows="5"
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

        <div style="display: flex; gap: 10px;">
            <button type="submit"
                    style="padding: 11px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Lưu thay đổi
            </button>

            <a href="{{ route('teacher.courses.index') }}"
               style="padding: 11px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
                Quay lại
            </a>
        </div>

    </form>
</div>

@include('footer')
