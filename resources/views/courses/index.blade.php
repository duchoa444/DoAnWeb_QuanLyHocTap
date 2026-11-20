{{-- Gọi Header từ file header.blade.php --}}
@include('header')

<div style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    <h2 style="margin-bottom: 20px;">Danh sách khóa học</h2>

    @if (session('success'))
        <div role="status" style="padding: 12px; margin-bottom: 20px; background: #e8f5e9; color: #2e7d32; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form tìm kiếm theo tên; lọc theo danh mục, giảng viên --}}
    <form method="GET" action="{{ route('courses.index') }}" style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 24px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <input type="text"
               name="keyword"
               value="{{ request('keyword') }}"
               placeholder="Tìm theo tên khóa học..."
               style="flex: 2; min-width: 220px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">

        <select name="category_id" style="flex: 1; min-width: 170px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">-- Tất cả danh mục --</option>
            @foreach(($categories ?? []) as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="teacher_id" style="flex: 1; min-width: 170px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">-- Tất cả giảng viên --</option>
            @foreach(($teachers ?? []) as $teacher)
                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" style="padding: 8px 18px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Tìm kiếm
        </button>

        @if(request()->filled('keyword') || request()->filled('category_id') || request()->filled('teacher_id'))
            <a href="{{ route('courses.index') }}" style="padding: 8px 14px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 4px;">
                Xóa lọc
            </a>
        @endif
    </form>

    {{-- Lưới hiển thị khóa học --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; align-items: stretch;">
        @forelse($courses as $course)
            <div style="display: flex; flex-direction: column;">
                @include('course-card', ['course' => $course])
            </div>
        @empty
            <p style="grid-column: 1 / -1; text-align: center; color: #666; padding: 30px; background: #fff; border: 1px solid #ddd; border-radius: 8px;">
                Không tìm thấy khóa học nào phù hợp.
            </p>
        @endforelse
    </div>

    {{-- Phân trang server (9 khóa/trang) --}}
    <div style="margin-top: 24px;">
        {{ $courses->links() }}
    </div>
</div>

{{-- Gọi Footer từ file footer.blade.php --}}
@include('footer')