@include('header')

<div style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    {{-- Tiêu đề trang --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0 0 4px 0; font-size: 24px; color: #1e293b;">Quản lý đề thi & Kết quả kiểm tra</h2>
            <p style="margin: 0; color: #64748b; font-size: 14px;">Quản lý ngân hàng câu hỏi cuối khóa và theo dõi điểm số học viên.</p>
        </div>
        <a href="{{ route('teacher.courses.index') }}"
           style="padding: 9px 16px; background: #f1f5f9; color: #334155; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">
            &larr; Danh sách khóa học
        </a>
    </div>

    {{-- 4 thẻ thống kê tổng quan --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 26px;">
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Tổng số đề thi</div>
            <div style="font-size: 24px; font-weight: 700; color: #0f172a;">{{ $exams->count() }}</div>
        </div>
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Tổng câu hỏi đã tạo</div>
            <div style="font-size: 24px; font-weight: 700; color: #2563eb;">{{ $totalQuestions }}</div>
        </div>
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Lượt học viên đã thi</div>
            <div style="font-size: 24px; font-weight: 700; color: #0f172a;">{{ $totalAttempts }}</div>
        </div>
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Tỷ lệ đạt (&ge; 5.0)</div>
            <div style="font-size: 24px; font-weight: 700; color: #16a34a;">{{ $passRate }}%</div>
        </div>
    </div>

    {{-- Bảng 1: Danh sách đề thi theo từng khóa học --}}
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 22px; margin-bottom: 28px;">
        <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #1e293b;">Danh sách đề thi cuối khóa</h3>

        @if($exams->isEmpty())
            <p style="margin: 0; color: #64748b; font-size: 14px;">Bạn chưa tạo khóa học nào.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left; color: #475569;">
                            <th style="padding: 12px;">Khóa học</th>
                            <th style="padding: 12px; text-align: center;">Số câu hỏi</th>
                            <th style="padding: 12px; text-align: center;">Thời gian / Lượt</th>
                            <th style="padding: 12px; text-align: center;">Trạng thái</th>
                            <th style="padding: 12px; text-align: center;">Điểm TB</th>
                            <th style="padding: 12px; text-align: right;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                            @php
                                $questionUrl = Route::has('teacher.questions.index')
                                    ? route('teacher.questions.index', $exam->id)
                                    : url('/teacher/courses/' . $exam->id . '/questions');
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px; font-weight: 600; color: #0f172a;">
                                    {{ $exam->title }}
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <strong>{{ $exam->question_count }}</strong> câu
                                </td>
                                <td style="padding: 12px; text-align: center; color: #475569;">
                                    {{ $exam->quiz_minutes ?? 30 }} phút &bull; {{ $exam->max_attempts ?? 3 }} lượt
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($exam->question_count > 0)
                                        <span style="padding: 4px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 600;">
                                            Sẵn sàng
                                        </span>
                                    @else
                                        <span style="padding: 4px 10px; background: #fef3c7; color: #92400e; border-radius: 999px; font-size: 12px; font-weight: 600;">
                                            Chưa có câu hỏi
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: center; color: #334155;">
                                    @if($exam->attempt_count > 0)
                                        <strong>{{ number_format($exam->avg_score, 1) }}/10</strong>
                                        <span style="color: #94a3b8; font-size: 12px;">({{ $exam->attempt_count }} lượt)</span>
                                    @else
                                        <span style="color: #94a3b8;">Chưa có bài nộp</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; white-space: nowrap;">
                                    <a href="{{ $questionUrl }}"
                                       style="display: inline-block; padding: 6px 12px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 5px; font-size: 13px; font-weight: 600;">
                                        Soạn câu hỏi
                                    </a>
                                    <a href="{{ route('teacher.courses.edit', $exam->id) }}"
                                       style="display: inline-block; padding: 6px 10px; background: #f1f5f9; color: #334155; text-decoration: none; border-radius: 5px; font-size: 13px; margin-left: 4px;">
                                        Cài đặt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

{{-- Bảng 2: Kết quả làm bài gần đây của học viên --}}
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 22px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <h3 style="margin: 0; font-size: 18px; color: #1e293b;">Kết quả thi gần đây của học viên</h3>

            <form method="GET" action="{{ url('/teacher/exams') }}" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <select name="course_id" onchange="this.form.submit()"
                        style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 5px; font-size: 13.5px;">
                    <option value="">-- Tất cả khóa học --</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ (string) $selectedCourseId === (string) $exam->id ? 'selected' : '' }}>
                            {{ $exam->title }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ Route::has('teacher.exams.export') ? route('teacher.exams.export', array_filter(['course_id' => $selectedCourseId])) : url('/teacher/exams/export' . ($selectedCourseId ? '?course_id=' . $selectedCourseId : '')) }}"
                   style="padding: 7px 14px; background: #16a34a; color: #fff; text-decoration: none; border-radius: 5px; font-size: 13.5px; font-weight: 600; white-space: nowrap;">
                    Xuất CSV
                </a>
            </form>
        </div>

        @if($recentAttempts->isEmpty())
            <p style="margin: 0; color: #64748b; font-size: 14px;">Chưa có học viên nào nộp bài kiểm tra.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left; color: #475569;">
                            <th style="padding: 12px;">Học viên</th>
                            <th style="padding: 12px;">Khóa học</th>
                            <th style="padding: 12px; text-align: center;">Điểm số</th>
                            <th style="padding: 12px; text-align: center;">Kết quả</th>
                            <th style="padding: 12px; text-align: right;">Thời gian nộp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAttempts as $item)
                            @php $passed = (float) $item->score >= 5.0; @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px;">
                                    <div style="font-weight: 600; color: #0f172a;">{{ $item->student_name ?? 'Học viên' }}</div>
                                    <div style="font-size: 12px; color: #64748b;">{{ $item->student_email ?? '' }}</div>
                                </td>
                                <td style="padding: 12px; color: #334155;">{{ $item->course_title }}</td>
                                <td style="padding: 12px; text-align: center; font-weight: 700; color: #0f172a;">
                                    {{ number_format((float) $item->score, 2) }} / 10
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($passed)
                                        <span style="padding: 4px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 600;">Đạt</span>
                                    @else
                                        <span style="padding: 4px 10px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 12px; font-weight: 600;">Chưa đạt</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: right; color: #64748b; font-size: 13px;">
                                    {{ \Carbon\Carbon::parse($item->submitted_at)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@include('footer')