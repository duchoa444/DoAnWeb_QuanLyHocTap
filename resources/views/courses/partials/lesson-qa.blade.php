@php
    $teacherId = (int) ($course->teacher_id ?? $course->user_id ?? 0);
    $qaComments = $comments ?? \App\Models\LessonComment::with(['user', 'replies.user'])
        ->where('lesson_id', $lesson->id)
        ->whereNull('parent_id')
        ->latest()
        ->get();
@endphp

<div id="qa-section" style="margin-top: 32px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 22px;">
    <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #1e293b;">
        Hỏi đáp trong bài học ({{ $qaComments->count() }})
    </h3>

    @if($errors->has('content'))
        <div style="padding: 10px 14px; margin-bottom: 14px; background: #fee2e2; color: #991b1b; border-radius: 6px; font-size: 13.5px;">
            {{ $errors->first('content') }}
        </div>
    @endif

    {{-- Form đặt câu hỏi mới --}}
    <form action="{{ route('lesson.comments.store', $lesson->id) }}" method="POST" style="margin-bottom: 24px;">
        @csrf
        <textarea name="content" rows="3" required
                  placeholder="Bạn có thắc mắc gì về nội dung bài học này? Nhập câu hỏi tại đây..."
                  style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; resize: vertical; box-sizing: border-box;">{{ old('content') }}</textarea>
        <div style="margin-top: 10px; text-align: right;">
            <button type="submit"
                    style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">
                Gửi câu hỏi
            </button>
        </div>
    </form>

    {{-- Danh sách câu hỏi & phản hồi --}}
    @if($qaComments->isEmpty())
        <p style="margin: 0; color: #64748b; font-size: 14px; text-align: center; padding: 16px 0;">
            Chưa có câu hỏi nào cho bài học này. Hãy là người đầu tiên đặt câu hỏi!
        </p>
    @else
        <div style="display: flex; flex-direction: column; gap: 18px;">
            @foreach($qaComments as $comment)
                @php
                    $isTeacherComment = (int) $comment->user_id === $teacherId;
                    $canDeleteComment = (int) auth()->id() === (int) $comment->user_id
                        || (int) auth()->id() === $teacherId
                        || (auth()->user()->role ?? '') === 'admin';
                @endphp

                <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; background: #f8fafc;">
                    {{-- Câu hỏi gốc --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 8px;">
                        <div>
                            <strong style="color: #0f172a; font-size: 14.5px;">{{ $comment->user->name ?? 'Người dùng' }}</strong>
                            @if($isTeacherComment)
                                <span style="margin-left: 6px; padding: 2px 8px; background: #dbeafe; color: #1d4ed8; border-radius: 999px; font-size: 11.5px; font-weight: 600;">
                                    Giảng viên
                                </span>
                            @endif
                            <span style="margin-left: 8px; color: #64748b; font-size: 12px;">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                                    style="background: none; border: none; color: #2563eb; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0;">
                                Trả lời
                            </button>

                            @if($canDeleteComment)
                                <form action="{{ route('lesson.comments.destroy', $comment->id) }}" method="POST" style="margin: 0;"
                                      onsubmit="return confirm('Xóa câu hỏi này và toàn bộ phản hồi bên dưới?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background: none; border: none; color: #dc2626; font-size: 13px; cursor: pointer; padding: 0;">
                                        Xóa
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div style="color: #334155; font-size: 14px; line-height: 1.55; white-space: pre-line;">{{ $comment->content }}</div>

                    {{-- Danh sách phản hồi (cấp 2) --}}
                    @if($comment->replies->isNotEmpty())
                        <div style="margin-top: 14px; padding-left: 16px; border-left: 3px solid #cbd5e1; display: flex; flex-direction: column; gap: 10px;">
                            @foreach($comment->replies as $reply)
                                @php
                                    $isTeacherReply = (int) $reply->user_id === $teacherId;
                                    $canDeleteReply = (int) auth()->id() === (int) $reply->user_id
                                        || (int) auth()->id() === $teacherId
                                        || (auth()->user()->role ?? '') === 'admin';
                                @endphp
                                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <div>
                                            <strong style="color: #0f172a; font-size: 13.5px;">{{ $reply->user->name ?? 'Người dùng' }}</strong>
                                            @if($isTeacherReply)
                                                <span style="margin-left: 6px; padding: 2px 8px; background: #dcfce7; color: #166534; border-radius: 999px; font-size: 11px; font-weight: 600;">
                                                    Giảng viên
                                                </span>
                                            @endif
                                            <span style="margin-left: 8px; color: #64748b; font-size: 11.5px;">
                                                {{ $reply->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        @if($canDeleteReply)
                                            <form action="{{ route('lesson.comments.destroy', $reply->id) }}" method="POST" style="margin: 0;"
                                                  onsubmit="return confirm('Xóa phản hồi này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        style="background: none; border: none; color: #dc2626; font-size: 12px; cursor: pointer; padding: 0;">
                                                    Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div style="color: #334155; font-size: 13.5px; line-height: 1.5; white-space: pre-line;">{{ $reply->content }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Form nhập phản hồi ẩn/hiện --}}
                    <form id="reply-form-{{ $comment->id }}"
                          action="{{ route('lesson.comments.store', $lesson->id) }}"
                          method="POST"
                          style="display: none; margin-top: 12px; padding-left: 16px;">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="content" rows="2" required
                                  placeholder="Nhập nội dung trả lời..."
                                  style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; box-sizing: border-box;"></textarea>
                        <div style="margin-top: 6px; display: flex; justify-content: flex-end; gap: 8px;">
                            <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                                    style="padding: 5px 12px; background: #e2e8f0; color: #334155; border: none; border-radius: 5px; font-size: 13px; cursor: pointer;">
                                Hủy
                            </button>
                            <button type="submit"
                                    style="padding: 5px 14px; background: #16a34a; color: #fff; border: none; border-radius: 5px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                Gửi trả lời
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        if (!form) return;
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    }
</script>