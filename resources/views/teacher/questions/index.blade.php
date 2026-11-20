@include('header')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-2">Quản lý câu hỏi trắc nghiệm</h1>
    <p class="text-gray-600 mb-6">Khóa học: <strong>{{ $course->title }}</strong></p>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form 1: Cài đặt thời gian làm bài --}}
    <div class="bg-white border rounded-lg p-5 mb-6 shadow-sm">
        <h2 class="text-lg font-semibold mb-3">Thời gian làm bài kiểm tra</h2>
        <form action="{{ route('teacher.questions.duration', $course->id) }}" method="POST" class="flex items-end gap-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số phút làm bài</label>
                <input type="number" name="quiz_minutes" min="1" max="180"
                       value="{{ old('quiz_minutes', $course->quiz_minutes ?? 15) }}"
                       class="border rounded px-3 py-2 w-40" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Lưu thời gian
            </button>
        </form>
    </div>

    {{-- Form 2: Thêm câu hỏi mới --}}
    <div class="bg-white border rounded-lg p-5 mb-6 shadow-sm">
        <h2 class="text-lg font-semibold mb-3">Thêm câu hỏi mới</h2>
        <form action="{{ route('teacher.questions.store', $course->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nội dung câu hỏi</label>
                <textarea name="content" rows="2" class="w-full border rounded px-3 py-2" required>{{ old('content') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Đáp án A</label>
                    <input type="text" name="option_a" value="{{ old('option_a') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Đáp án B</label>
                    <input type="text" name="option_b" value="{{ old('option_b') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Đáp án C</label>
                    <input type="text" name="option_c" value="{{ old('option_c') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Đáp án D</label>
                    <input type="text" name="option_d" value="{{ old('option_d') }}" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Đáp án đúng</label>
                <select name="correct_option" class="border rounded px-3 py-2 w-48" required>
                    <option value="">-- Chọn đáp án --</option>
                    <option value="A">Đáp án A</option>
                    <option value="B">Đáp án B</option>
                    <option value="C">Đáp án C</option>
                    <option value="D">Đáp án D</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Thêm câu hỏi
            </button>
        </form>
    </div>

    {{-- Danh sách câu hỏi hiện có --}}
    <div class="bg-white border rounded-lg p-5 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Danh sách câu hỏi ({{ $questions->count() }} câu)</h2>
        @forelse ($questions as $index => $question)
            <div class="border-b py-4 last:border-b-0">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <p class="font-medium">Câu {{ $index + 1 }}: {{ $question->content }}</p>
                        <ul class="mt-2 text-sm space-y-1 text-gray-700">
                            <li class="{{ $question->correct_option === 'A' ? 'text-green-700 font-semibold' : '' }}">A. {{ $question->option_a }}</li>
                            <li class="{{ $question->correct_option === 'B' ? 'text-green-700 font-semibold' : '' }}">B. {{ $question->option_b }}</li>
                            <li class="{{ $question->correct_option === 'C' ? 'text-green-700 font-semibold' : '' }}">C. {{ $question->option_c }}</li>
                            <li class="{{ $question->correct_option === 'D' ? 'text-green-700 font-semibold' : '' }}">D. {{ $question->option_d }}</li>
                        </ul>
                    </div>
                    <form action="{{ route('teacher.questions.destroy', [$course->id, $question->id]) }}" method="POST"
                          onsubmit="return confirm('Xác nhận xóa câu hỏi này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Chưa có câu hỏi nào cho khóa học này.</p>
        @endforelse
    </div>
</div>
@include('footer')