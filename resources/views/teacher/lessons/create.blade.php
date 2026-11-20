
@include('header')

<div style="max-width: 900px; margin: 20px auto; padding: 20px;">

    <h2 style="margin-bottom: 8px;">Thêm bài học mới</h2>

    <p style="color: #666; margin-bottom: 24px;">
        Khóa học: <strong>{{ $course->title }}</strong>
    </p>

    @if($errors->any())
        <div style="padding: 12px; margin-bottom: 20px; background: #f8d7da; color: #842029; border-radius: 5px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.lessons.store', $course->id) }}"
          method="POST"
          enctype="multipart/form-data"
          style="background: white; padding: 24px; border: 1px solid #ddd; border-radius: 8px;">

        @csrf

        <div style="margin-bottom: 20px;">
            <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px;">
                Tiêu đề bài học <span style="color: red;">*</span>
            </label>

            <input type="text" id="title" name="title"
                   value="{{ old('title') }}"
                   maxlength="255"
                   required
                   placeholder="Nhập tiêu đề bài học..."
                   style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="content" style="display: block; font-weight: bold; margin-bottom: 8px;">
                Nội dung bài học
            </label>

            <textarea id="content" name="content" rows="7"
                      placeholder="Nhập nội dung văn bản bài học..."
                      style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px; resize: vertical;">{{ old('content') }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="video_url" style="display: block; font-weight: bold; margin-bottom: 8px;">
                Link video YouTube
            </label>

            <input type="url" id="video_url" name="video_url"
                   value="{{ old('video_url') }}"
                   placeholder="https://www.youtube.com/watch?v=..."
                   style="width: 100%; box-sizing: border-box; padding: 11px; border: 1px solid #ccc; border-radius: 5px;">

            <small style="color: #666;">
                Chỉ nhập đường dẫn video YouTube hợp lệ.
            </small>
        </div>

        <div style="margin-bottom: 24px;">
            <label for="pdf" style="display: block; font-weight: bold; margin-bottom: 8px;">
                Tệp tài liệu PDF
            </label>

            <input type="file" id="pdf" name="pdf" accept=".pdf,application/pdf">

            <p style="color: #666; font-size: 13px; margin-top: 8px;">
                Chỉ chấp nhận tệp PDF, dung lượng tối đa 5 MB.
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="submit"
                    style="padding: 11px 22px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Lưu bài học
            </button>

            <a href="{{ route('teacher.lessons.index', $course->id) }}"
               style="padding: 11px 22px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
                Hủy
            </a>
        </div>

    </form>
</div>

@include('footer')
