<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LessonCommentController extends Controller
{
    /**
     * Đăng câu hỏi mới hoặc trả lời câu hỏi trong bài học (HT-08)
     */
    public function store(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'content'   => ['required', 'string', 'min:2', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:lesson_comments,id'],
        ], [
            'content.required' => 'Vui lòng nhập nội dung câu hỏi hoặc phản hồi.',
            'content.min'      => 'Nội dung cần ít nhất 2 ký tự.',
            'content.max'      => 'Nội dung không được vượt quá 2000 ký tự.',
        ]);

        $user = Auth::user();
        $course = $lesson->course;

        if (!$this->canParticipate($user, $course)) {
            return redirect()
                ->back()
                ->with('error', 'Bạn cần đăng ký khóa học này để tham gia hỏi đáp.');
        }

        $parentId = null;
        if (!empty($validated['parent_id'])) {
            $parentComment = LessonComment::where('id', $validated['parent_id'])
                ->where('lesson_id', $lesson->id)
                ->first();

            if (!$parentComment) {
                return redirect()->back()->with('error', 'Câu hỏi gốc không hợp lệ.');
            }

            // Nếu trả lời một phản hồi con, tự động gắn về câu hỏi cha gốc (giữ cấu trúc 2 cấp)
            $parentId = $parentComment->parent_id ?? $parentComment->id;
        }

        LessonComment::create([
            'lesson_id' => $lesson->id,
            'user_id'   => $user->id,
            'parent_id' => $parentId,
            'content'   => trim($validated['content']),
        ]);

        return redirect(url()->previous() . '#qa-section')
            ->with('success', $parentId ? 'Đã gửi phản hồi thành công.' : 'Đã đăng câu hỏi thành công.');
    }

    /**
     * Xóa câu hỏi hoặc phản hồi (chủ bình luận, giảng viên khóa học hoặc admin)
     */
    public function destroy(LessonComment $comment)
    {
        $user = Auth::user();
        $course = $comment->lesson?->course;

        $teacherId = (int) ($course->teacher_id ?? $course->user_id ?? 0);
        $isCourseTeacher = $teacherId === (int) $user->id;
        $isOwner = (int) $comment->user_id === (int) $user->id;
        $isAdmin = ($user->role ?? '') === 'admin';

        if (!$isOwner && !$isCourseTeacher && !$isAdmin) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này.');
        }

        $comment->delete();

        return redirect(url()->previous() . '#qa-section')
            ->with('success', 'Đã xóa nội dung hỏi đáp.');
    }

    /**
     * Kiểm tra quyền hỏi đáp: giảng viên khóa học, admin hoặc học viên đã đăng ký khóa
     */
    private function canParticipate($user, $course): bool
    {
        if (!$user || !$course) {
            return false;
        }

        if (($user->role ?? '') === 'admin') {
            return true;
        }

        $teacherId = (int) ($course->teacher_id ?? $course->user_id ?? 0);
        if ($teacherId === (int) $user->id) {
            return true;
        }

        if (!Schema::hasTable('enrollments')) {
            return true;
        }

        $cols = Schema::getColumnListing('enrollments');
        $userCol = in_array('user_id', $cols) ? 'user_id' : 'student_id';

        return DB::table('enrollments')
            ->where('course_id', $course->id)
            ->where($userCol, $user->id)
            ->exists();
    }
}