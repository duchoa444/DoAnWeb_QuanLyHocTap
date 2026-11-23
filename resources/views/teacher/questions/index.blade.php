@include('header')

<div style="max-width: 1000px; margin: 24px auto; padding: 0 20px;">

    {{-- Điều hướng & Tiêu đề --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
        <div>
            <a href="{{ route('teacher.courses.edit', $course->id) }}"
               style="color: #2563eb; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 6px;">
                &larr; Quay lại chỉnh sửa khóa học
            </a>
            <h2 style="margin: 0; font-size: 24px; color: #0f172a;">Đề thi: {{ $course->title }}</h2>
        </div>

        <form action="{{ route('teacher.questions.duration', $course->id) }}" method="POST"
              style="display: flex; align-items: center; gap: 8px; background: #f1f5f9; padding: 8px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
            @csrf
            @method('PATCH')
            <label for="quiz_minutes" style="font-size: 13.5px; font-weight: 600; color: #334155;">Thời gian làm bài:</label>
            <input type="number" id="quiz_minutes" name="quiz_minutes" min="1" max="180"
                   value="{{ $course->quiz_minutes ?? 30 }}"
                   style="width: 65px; padding: 4px 8px; border: 1px solid #cbd5e1; border-radius: 5px; font-size: 13.5px; text-align: center;">
            <span style="font-size: 13.5px; color: #334155;">phút</span>
            <button type="submit"
                    style="padding: 5px 10px; background: #334155; color: #fff; border: none; border-radius: 5px; font-size: 12.5px; cursor: pointer;">
                Lưu
            </button>
        </form>
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
        <div style="padding: 12px 16px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding: 12px 16px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Khối 1: Form thêm câu hỏi mới --}}
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px; margin-bottom: 24px;">
        <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #0f172a;">Thêm câu hỏi trắc nghiệm</h3>

        <form action="{{ route('teacher.questions.store', $course->id) }}" method="POST">
            @csrf

            <div style="margin-bottom: 12px;">
                <input type="text" name="content" value="{{ old('content') }}"
                       placeholder="Nội dung câu hỏi" required
                       style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-bottom: 12px;">
                <input type="text" name="option_a" value="{{ old('option_a') }}" placeholder="Đáp án A" required
                       style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                <input type="text" name="option_b" value="{{ old('option_b') }}" placeholder="Đáp án B" required
                       style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                <input type="text" name="option_c" value="{{ old('option_c') }}" placeholder="Đáp án C" required
                       style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                <input type="text" name="option_d" value="{{ old('option_d') }}" placeholder="Đáp án D" required
                       style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <select name="correct_option" required
                        style="flex: 1; min-width: 220px; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; background: #fff;">
                    <option value="">Đáp án đúng</option>
                    <option value="A" {{ old('correct_option') === 'A' ? 'selected' : '' }}>Đáp án A</option>
                    <option value="B" {{ old('correct_option') === 'B' ? 'selected' : '' }}>Đáp án B</option>
                    <option value="C" {{ old('correct_option') === 'C' ? 'selected' : '' }}>Đáp án C</option>
                    <option value="D" {{ old('correct_option') === 'D' ? 'selected' : '' }}>Đáp án D</option>
                </select>

                <button type="submit"
                        style="padding: 10px 24px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Thêm câu hỏi
                </button>
            </div>

            <p style="margin: 10px 0 0 0; font-size: 13px; color: #64748b;">Thêm vào bài kiểm tra cuối khóa.</p>
        </form>
    </div>

    {{-- Khối 2: Danh sách câu hỏi hiện có (có nút Sửa & Xóa) --}}
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px;">
        <h3 style="margin: 0 0 18px 0; font-size: 18px; color: #0f172a;">
            Danh sách câu hỏi hiện có ({{ $questions->count() }} câu)
        </h3>

        @forelse($questions as $index => $question)
            @php
                $correct = strtoupper((string) $question->correct_option);
            @endphp

            <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 14px; background: #fff;">
                {{-- Chế độ hiển thị câu hỏi --}}
                <div id="question-view-{{ $question->id }}">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                        <strong style="color: #0f172a; font-size: 15px;">
                            Câu {{ $index + 1 }}: {{ $question->content }}
                        </strong>

                        <div style="display: flex; align-items: center; gap: 14px; white-space: nowrap;">
                            <button type="button"
                                    onclick="toggleEditQuestion({{ $question->id }})"
                                    style="background: none; border: none; color: #2563eb; font-size: 13.5px; font-weight: 600; cursor: pointer; padding: 0;">
                                Sửa
                            </button>

                            <form action="{{ route('teacher.questions.destroy', [$course->id, $question->id]) }}"
                                  method="POST"
                                  style="margin: 0;"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="background: none; border: none; color: #dc2626; font-size: 13.5px; font-weight: 600; cursor: pointer; padding: 0;">
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; font-size: 13.5px;">
                        @foreach(['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $key => $optText)
                            @php $isCorrect = $correct === $key; @endphp
                            <div style="padding: 9px 12px; border-radius: 6px; border: 1px solid {{ $isCorrect ? '#bbf7d0' : '#e2e8f0' }}; background: {{ $isCorrect ? '#dcfce7' : '#fff' }}; color: {{ $isCorrect ? '#166534' : '#334155' }}; font-weight: {{ $isCorrect ? '700' : '400' }};">
                                {{ $key }}. {{ $optText }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Chế độ chỉnh sửa câu hỏi --}}
                <form id="question-edit-{{ $question->id }}"
                      action="{{ route('teacher.questions.update', [$course->id, $question->id]) }}"
                      method="POST"
                      style="display: none;">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 10px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 4px;">
                            Chỉnh sửa câu {{ $index + 1 }}
                        </label>
                        <input type="text" name="content" value="{{ $question->content }}" required
                               style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-bottom: 10px;">
                        <input type="text" name="option_a" value="{{ $question->option_a }}" placeholder="Đáp án A" required
                               style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; box-sizing: border-box;">
                        <input type="text" name="option_b" value="{{ $question->option_b }}" placeholder="Đáp án B" required
                               style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; box-sizing: border-box;">
                        <input type="text" name="option_c" value="{{ $question->option_c }}" placeholder="Đáp án C" required
                               style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; box-sizing: border-box;">
                        <input type="text" name="option_d" value="{{ $question->option_d }}" placeholder="Đáp án D" required
                               style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; box-sizing: border-box;">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <select name="correct_option" required
                                style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; min-width: 180px;">
                            <option value="A" {{ $correct === 'A' ? 'selected' : '' }}>Đáp án đúng: A</option>
                            <option value="B" {{ $correct === 'B' ? 'selected' : '' }}>Đáp án đúng: B</option>
                            <option value="C" {{ $correct === 'C' ? 'selected' : '' }}>Đáp án đúng: C</option>
                            <option value="D" {{ $correct === 'D' ? 'selected' : '' }}>Đáp án đúng: D</option>
                        </select>

                        <div style="display: flex; gap: 8px;">
                            <button type="button"
                                    onclick="toggleEditQuestion({{ $question->id }})"
                                    style="padding: 8px 14px; background: #e2e8f0; color: #334155; border: none; border-radius: 6px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
                                Hủy
                            </button>
                            <button type="submit"
                                    style="padding: 8px 16px; background: #16a34a; color: #fff; border: none; border-radius: 6px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
                                Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @empty
            <p style="margin: 0; color: #64748b; font-size: 14px; text-align: center; padding: 20px 0;">
                Chưa có câu hỏi nào trong đề thi này. Hãy thêm câu hỏi ở biểu mẫu phía trên.
            </p>
        @endforelse
    </div>

</div>

<script>
    function toggleEditQuestion(id) {
        const viewBox = document.getElementById('question-view-' + id);
        const editForm = document.getElementById('question-edit-' + id);
        if (!viewBox || !editForm) return;

        const isEditing = editForm.style.display === 'block';
        editForm.style.display = isEditing ? 'none' : 'block';
        viewBox.style.display  = isEditing ? 'block' : 'none';
    }
</script>

@include('footer')