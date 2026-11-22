<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LessonComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QnaController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = (int) Auth::id();
        $courseCols = Schema::getColumnListing('courses');
        $ownerCol = in_array('teacher_id', $courseCols) ? 'teacher_id' : 'user_id';

        // Lấy danh sách khóa học thuộc quyền quản lý của giáo viên
        $courses = DB::table('courses')
            ->where($ownerCol, $teacherId)
            ->select('id', 'title')
            ->orderByDesc('id')
            ->get();

        $courseIds = $courses->pluck('id')->all();

        $selectedCourseId = $request->query('course_id');
        $status = $request->query('status', 'all'); // all | unanswered | answered

        // Đếm số câu hỏi gốc chưa có phản hồi từ giáo viên
        $unansweredCount = empty($courseIds) ? 0 : LessonComment::whereNull('parent_id')
            ->whereHas('lesson', function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })
            ->whereDoesntHave('replies', function ($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            })
            ->count();

        // Truy vấn danh sách câu hỏi của sinh viên
        $query = LessonComment::with(['user', 'lesson.course', 'replies.user'])
            ->whereNull('parent_id')
            ->whereHas('lesson', function ($q) use ($courseIds, $selectedCourseId) {
                if (empty($courseIds)) {
                    $q->whereRaw('1 = 0');
                    return;
                }
                $q->whereIn('course_id', $courseIds);
                if ($selectedCourseId) {
                    $q->where('course_id', (int) $selectedCourseId);
                }
            })
            ->latest();

        if ($status === 'unanswered') {
            $query->whereDoesntHave('replies', function ($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            });
        } elseif ($status === 'answered') {
            $query->whereHas('replies', function ($q) use ($teacherId) {
                $q->where('user_id', $teacherId);
            });
        }

        $comments = $query->paginate(15)->withQueryString();

        return view('teacher.qna.index', compact(
            'courses',
            'comments',
            'selectedCourseId',
            'status',
            'unansweredCount'
        ));
    }
}