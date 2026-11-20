
@include('header')

<div style="max-width: 850px; margin: 30px auto; padding: 20px;">

    <h2>Thêm khóa học mới</h2>
    <p style="color: #666;">Nhập thông tin để tạo khóa học của bạn.</p>

    @if ($errors->any())
        <div style="padding: 12px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.courses.store') }}" method="POST"
          style="background: white; padding: 25px; border: 1px solid #ddd; border-radius: 8px;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Tên khóa học
            </label>

            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="Nhập tên khóa học"
                   required maxlength="255"
                   style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Danh mục
            </label>

            <select name="category_id" required
                    style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="">-- Chọn danh mục --</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                      placeholder="Nhập mô tả khóa học..."
                      style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">
                Thời gian làm bài kiểm tra (phút)
            </label>

            <input type="number" name="quiz_minutes"
                   value="{{ old('quiz_minutes', 30) }}"
                   min="1" required
                   style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit"
                    style="padding: 11px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Tạo khóa học
            </button>

            <a href="{{ route('teacher.courses.index') }}"
               style="padding: 11px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
                Quay lại
            </a>
        </div>
    </form>

</div>

@include('footer')
