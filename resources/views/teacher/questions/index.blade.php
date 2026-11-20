@include('header')

<div style="max-width: 850px; margin: 30px auto; padding: 20px;">

    {{-- Thanh điều hướng trên cùng --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
        <div>
            <a href="{{ route('teacher.courses.edit', $course->id) }}"
               style="color: #007bff; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 4px;">
                &larr; Quay lại chỉnh sửa khóa học
            </a>
            <h2 style="margin: 0; font-size: 22px; color: #222;">
                Đề thi: {{ $course->title }}
            </h2>
        </div>

        <span style="padding: 6px 12px; background: #f1f5f9; color: #475569; border-radius: 5px; font-size: 13px; font-weight: 600;">
            Thời gian làm bài: {{ $course->quiz_minutes ?? 30 }} phút
        </span>
    </div>

    @if ($errors->any())
        <div style="padding: 12px 16px; margin-bottom: 18px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- KHUNG THÊM CÂU HỎI TRẮC NGHIỆM (THEO MẪU THIẾT KẾ) --}}
    <div style="background: #fff; padding: 28px; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 28px;">
        <h3 style="margin: 0 0 18px 0; font-size: 20px; font-weight: 700; color: #222;">
            Thêm câu hỏi trắc nghiệm
        </h3>

        <form action="{{ Route::has('teacher.questions.store') ? route('teacher.questions.store', $course->id) : url('/teacher/courses/' . $course->id . '/questions') }}"
              method="POST">
            @csrf

            {{-- Hàng 1: Nội dung câu hỏi --}}
            <div style="margin-bottom: 14px;">
                <input type="text"
                       name="content"
                       value="{{ old('content') }}"
                       required
                       placeholder="Nội dung câu hỏi"
                       style="width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid #bbb; border-radius: 4px; font-size: 14.5px; color: #222;">
            </div>

            {{-- Hàng 2: 4 lựa chọn Đáp án A / B / C / D --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                <input type="text"
                       name="option_a"
                       value="{{ old('option_a') }}"
                       required
                       placeholder="Đáp án A"
                       style="width: 100%; box-sizing: border-box; padding: 11px 14px; border: 1px solid #bbb; border-radius: 4px; font-size: 14px;">

                <input type="text"
                       name="option_b"
                       value="{{ old('option_b') }}"
                       required
                       placeholder="Đáp án B"
                       style="width: 100%; box-sizing: border-box; padding: 11px 14px; border: 1px solid #bbb; border-radius: 4px; font-size: 14px;">

                <input type="text"
                       name="option_c"
                       value="{{ old('option_c') }}"
                       required
                       placeholder="Đáp án C"
                       style="width: 100%; box-sizing: border-box; padding: 11px 14px; border: 1px solid #bbb; border-radius: 4px; font-size: 14px;">

                <input type="text"
                       name="option_d"
                       value="{{ old('option_d') }}"
                       required
                       placeholder="Đáp án D"
                       style="width: 100%; box-sizing: border-box; padding: 11px 14px; border: 1px solid #bbb; border-radius: 4px; font-size: 14px;">
            </div>

            {{-- Hàng 3: Dropdown Đáp án đúng + Nút Thêm câu hỏi nằm ngang hàng --}}
            <div style="display: flex; gap: 12px; align-items: stretch; margin-bottom: 14px;">
                <select name="correct_answer"
                        required
                        style="flex: 1; padding: 12px 14px; border: 1px solid #bbb; border-radius: 4px; font-size: 14.5px; color: #333; background: #fff; cursor: pointer;">
                    <option value="" disabled {{ old('correct_answer') ? '' : 'selected' }}>Đáp án đúng</option>
                    <option value="A" {{ old('correct_answer') === 'A' ? 'selected' : '' }}>Đáp án A</option>
                    <option value="B" {{ old('correct_answer') === 'B' ? 'selected' : '' }}>Đáp án B</option>
                    <option value="C" {{ old('correct_answer') === 'C' ? 'selected' : '' }}>Đáp án C</option>
                    <option value="D" {{ old('correct_answer') === 'D' ? 'selected' : '' }}>Đáp án D</option>
                </select>

                <button type="submit"
                        style="padding: 12px 26px; background: #2563eb; color: #fff; border: none; border-radius: 4px; font-size: 14.5px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                    Thêm câu hỏi
                </button>
            </div>

            {{-- Ghi chú chân thẻ --}}
            <p style="margin: 0; font-size: 13px; color: #666;">
                Thêm vào bài kiểm tra cuối khóa.
            </p>
        </form>
    </div>

    {{-- DANH SÁCH CÂU HỎI ĐÃ TẠO --}}
    @php
        $questionList = $questions ?? $course->questions ?? collect();
    @endphp

    <div style="background: #fff; padding: 24px 28px; border: 1px solid #ddd; border-radius: 6px;">
        <h3 style="margin: 0 0 16px 0; font-size: 17px; color: #222;">
            Danh sách câu hỏi hiện có ({{ count($questionList) }} câu)
        </h3>

        @if(count($questionList) === 0)
            <p style="margin: 0; color: #777; font-size: 14px;">
                Chưa có câu hỏi nào trong bài kiểm tra cuối khóa.
            </p>
        @else
            @foreach($questionList as $index => $q)
                @php
                    $qContent = $q->content ?? $q->question_text ?? $q->question ?? '';
                    $qCorrect = strtoupper($q->correct_answer ?? $q->correct_option ?? $q->answer ?? '');
                @endphp

                <div style="padding: 16px; border: 1px solid #e5e7eb; border-radius: 6px; margin-bottom: 12px; background: #fafafa;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 10px;">
                        <div style="font-weight: 700; color: #111; font-size: 15px;">
                            Câu {{ $index + 1 }}: {{ $qContent }}
                        </div>

                        <form action="{{ Route::has('teacher.questions.destroy') ? route('teacher.questions.destroy', [$course->id, $q->id]) : url('/teacher/courses/' . $course->id . '/questions/' . $q->id) }}"
                              method="POST"
                              onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này?');"
                              style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="background: none; border: none; color: #dc3545; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0;">
                                Xóa
                            </button>
                        </form>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 14px; color: #444;">
                        <div style="padding: 6px 10px; border-radius: 4px; {{ $qCorrect === 'A' ? 'background: #dcfce7; color: #166534; font-weight: 700;' : 'background: #fff; border: 1px solid #eee;' }}">
                            A. {{ $q->option_a }}
                        </div>
                        <div style="padding: 6px 10px; border-radius: 4px; {{ $qCorrect === 'B' ? 'background: #dcfce7; color: #166534; font-weight: 700;' : 'background: #fff; border: 1px solid #eee;' }}">
                            B. {{ $q->option_b }}
                        </div>
                        <div style="padding: 6px 10px; border-radius: 4px; {{ $qCorrect === 'C' ? 'background: #dcfce7; color: #166534; font-weight: 700;' : 'background: #fff; border: 1px solid #eee;' }}">
                            C. {{ $q->option_c }}
                        </div>
                        <div style="padding: 6px 10px; border-radius: 4px; {{ $qCorrect === 'D' ? 'background: #dcfce7; color: #166534; font-weight: 700;' : 'background: #fff; border: 1px solid #eee;' }}">
                            D. {{ $q->option_d }}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>

@include('footer')