@include('header')

<div style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    {{-- Tiêu đề & số câu hỏi chờ phản hồi --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0 0 4px 0; font-size: 24px; color: #1e293b;">Hỏi đáp từ học viên</h2>
            <p style="margin: 0; color: #64748b; font-size: 14px;">
                Theo dõi và giải đáp thắc mắc của sinh viên trong từng bài giảng.
            </p>
        </div>

        <div style="padding: 8px 16px; background: {{ $unansweredCount > 0 ? '#fef3c7' : '#dcfce7' }}; color: {{ $unansweredCount > 0 ? '#92400e' : '#166534' }}; border-radius: 8px; font-size: 14px; font-weight: 600;">
            {{ $unansweredCount > 0 ? "Còn {$unansweredCount} câu hỏi chưa trả lời" : 'Đã trả lời tất cả câu hỏi' }}
        </div>
    </div>


    @if(session('error'))
        <div style="padding: 12px 16px; margin-bottom: 16px; background: #fee2e2; color: #991b1b; border-radius: 6px; font-size: 14px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Thanh bộ lọc theo khóa học và trạng thái --}}
    <form method="GET" action="{{ route('teacher.qna.index') }}"
          style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 22px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <select name="course_id" onchange="this.form.submit()"
                style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; min-width: 240px;">
            <option value="">-- Tất cả khóa học của tôi --</option>
            @foreach($courses as $c)
                <option value="{{ $c->id }}" {{ (string) $selectedCourseId === (string) $c->id ? 'selected' : '' }}>
                    {{ $c->title }}
                </option>
            @endforeach
        </select>

        <select name="status" onchange="this.form.submit()"
                style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tất cả câu hỏi</option>
            <option value="unanswered" {{ $status === 'unanswered' ? 'selected' : '' }}>Chưa trả lời</option>
            <option value="answered" {{ $status === 'answered' ? 'selected' : '' }}>Đã trả lời</option>
        </select>

        @if($selectedCourseId || $status !== 'all')
            <a href="{{ route('teacher.qna.index') }}"
               style="padding: 8px 14px; background: #f1f5f9; color: #334155; text-decoration: none; border-radius: 6px; font-size: 13.5px;">
                Xóa bộ lọc
            </a>
        @endif
    </form>

    {{-- Danh sách câu hỏi --}}
    @if($comments->isEmpty())
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 36px; text-align: center; color: #64748b;">
            Không tìm thấy câu hỏi nào phù hợp với bộ lọc hiện tại.
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 18px;">
            @foreach($comments as $comment)
                @php
                    $teacherReplied = $comment->replies->contains('user_id', auth()->id());
                @endphp

                <div style="background: #fff; border: 1px solid {{ $teacherReplied ? '#e2e8f0' : '#fcd34d' }}; border-radius: 8px; padding: 20px;">
                    {{-- Header thông tin khóa học & bài học --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; gap: 8px;">
                        <div style="font-size: 13px; color: #475569;">
                            <span style="font-weight: 600; color: #2563eb;">{{ $comment->lesson->course->title ?? 'Khóa học' }}</span>
                            <span style="margin: 0 6px;">&rsaquo;</span>
                            <span>Bài học: <strong>{{ $comment->lesson->title ?? 'Bài giảng' }}</strong></span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            @if($teacherReplied)
                                <span style="padding: 3px 10px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 12px; font-weight: 600;">
                                    Đã phản hồi
                                </span>
                            @else
                                <span style="padding: 3px 10px; background: #fef3c7; color: #92400e; border-radius: 999px; font-size: 12px; font-weight: 600;">
                                    Chờ trả lời
                                </span>
                            @endif

                            <form action="{{ route('lesson.comments.destroy', $comment->id) }}" method="POST" style="margin: 0;"
                                  onsubmit="return confirm('Xóa câu hỏi này của sinh viên?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 13px; cursor: pointer;">
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Nội dung câu hỏi của sinh viên --}}
                    <div style="margin-bottom: 14px;">
                        <div style="margin-bottom: 6px;">
                            <strong style="color: #0f172a; font-size: 14.5px;">{{ $comment->user->name ?? 'Sinh viên' }}</strong>
                            <span style="margin-left: 8px; color: #64748b; font-size: 12.5px;">
                                {{ $comment->created_at->format('d/m/Y H:i') }} ({{ $comment->created_at->diffForHumans() }})
                            </span>
                        </div>
                        <div style="color: #1e293b; font-size: 14.5px; line-height: 1.55; white-space: pre-line;">{{ $comment->content }}</div>
                    </div>

                    {{-- Các câu trả lời đã có --}}
                    @if($comment->replies->isNotEmpty())
                        <div style="margin: 14px 0; padding-left: 16px; border-left: 3px solid #cbd5e1; display: flex; flex-direction: column; gap: 10px;">
                            @foreach($comment->replies as $reply)
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <div>
                                            <strong style="font-size: 13.5px; color: #0f172a;">{{ $reply->user->name ?? 'Người dùng' }}</strong>
                                            @if((int) $reply->user_id === (int) auth()->id())
                                                <span style="margin-left: 6px; padding: 2px 8px; background: #dbeafe; color: #1d4ed8; border-radius: 999px; font-size: 11px; font-weight: 600;">
                                                    Giảng viên
                                                </span>
                                            @endif
                                            <span style="margin-left: 8px; color: #64748b; font-size: 12px;">
                                                {{ $reply->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <form action="{{ route('lesson.comments.destroy', $reply->id) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirm('Xóa phản hồi này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 12px; cursor: pointer;">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                    <div style="color: #334155; font-size: 13.5px; line-height: 1.5; white-space: pre-line;">{{ $reply->content }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Form trả lời nhanh của giáo viên --}}
                    <form action="{{ route('lesson.comments.store', $comment->lesson_id) }}" method="POST" style="margin-top: 12px;">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div style="display: flex; gap: 10px; align-items: flex-start;">
                            <textarea name="content" rows="2" required
                                      placeholder="Nhập câu trả lời giải đáp cho sinh viên..."
                                      style="flex: 1; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; resize: vertical;"></textarea>
                            <button type="submit"
                                    style="padding: 9px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 13.5px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                Gửi trả lời
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 20px;">
            {{ $comments->links() }}
        </div>
    @endif

</div>

@include('footer')