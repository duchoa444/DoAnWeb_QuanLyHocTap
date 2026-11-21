<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = (int) Auth::id();

        $courseCols = Schema::getColumnListing('courses');
        $ownerCol = in_array('teacher_id', $courseCols) ? 'teacher_id' : 'user_id';

        // Lấy danh sách khóa học thuộc giáo viên hiện tại
        $courses = DB::table('courses')
            ->where($ownerCol, $teacherId)
            ->orderByDesc('id')
            ->get();

        $courseIds = $courses->pluck('id')->all();

        // Đếm số câu hỏi theo từng khóa học
        $questionCounts = Schema::hasTable('questions') && !empty($courseIds)
            ? DB::table('questions')
                ->select('course_id', DB::raw('COUNT(*) as total'))
                ->whereIn('course_id', $courseIds)
                ->groupBy('course_id')
                ->pluck('total', 'course_id')
            : collect();

        // Thống kê lượt thi đã nộp theo từng khóa học
        $attemptStats = Schema::hasTable('quiz_attempts') && !empty($courseIds)
            ? DB::table('quiz_attempts')
                ->select(
                    'course_id',
                    DB::raw('COUNT(*) as total_attempts'),
                    DB::raw('ROUND(AVG(score), 2) as avg_score'),
                    DB::raw('SUM(CASE WHEN score >= 5 THEN 1 ELSE 0 END) as passed_count')
                )
                ->whereIn('course_id', $courseIds)
                ->whereNotNull('submitted_at')
                ->groupBy('course_id')
                ->get()
                ->keyBy('course_id')
            : collect();

        // Gắn số liệu vào từng khóa học
        $exams = $courses->map(function ($course) use ($questionCounts, $attemptStats) {
            $stat = $attemptStats->get($course->id);
            $course->question_count = (int) ($questionCounts[$course->id] ?? 0);
            $course->attempt_count  = (int) ($stat->total_attempts ?? 0);
            $course->avg_score      = $stat && $stat->avg_score !== null ? (float) $stat->avg_score : null;
            $course->passed_count   = (int) ($stat->passed_count ?? 0);
            return $course;
        });

        // Danh sách lượt nộp bài gần đây của học viên (có hỗ trợ lọc theo khóa học)
        $selectedCourseId = $request->query('course_id');
        $recentAttempts = collect();

        if (Schema::hasTable('quiz_attempts') && !empty($courseIds)) {
            $attemptCols = Schema::getColumnListing('quiz_attempts');
            $studentCol = in_array('user_id', $attemptCols) ? 'user_id' : 'student_id';

            $query = DB::table('quiz_attempts as qa')
                ->join('courses as c', 'c.id', '=', 'qa.course_id')
                ->leftJoin('users as u', 'u.id', '=', 'qa.' . $studentCol)
                ->whereIn('qa.course_id', $courseIds)
                ->whereNotNull('qa.submitted_at')
                ->select(
                    'qa.id',
                    'qa.score',
                    'qa.submitted_at',
                    'c.id as course_id',
                    'c.title as course_title',
                    'u.name as student_name',
                    'u.email as student_email'
                )
                ->orderByDesc('qa.submitted_at');

            if ($selectedCourseId) {
                $query->where('qa.course_id', (int) $selectedCourseId);
            }

            $recentAttempts = $query->limit(30)->get();
        }

        $totalQuestions = $exams->sum('question_count');
        $totalAttempts  = $exams->sum('attempt_count');
        $totalPassed    = $exams->sum('passed_count');
        $passRate       = $totalAttempts > 0 ? round(($totalPassed / $totalAttempts) * 100, 1) : 0;

        return view('teacher.exams.index', compact(
            'exams',
            'recentAttempts',
            'selectedCourseId',
            'totalQuestions',
            'totalAttempts',
            'passRate'
        ));
    }
    public function export(Request $request)
    {
        $teacherId = (int) Auth::id();
        $courseCols = Schema::getColumnListing('courses');
        $ownerCol = in_array('teacher_id', $courseCols) ? 'teacher_id' : 'user_id';

        // Chỉ lấy danh sách khóa học thuộc quyền quản lý của giáo viên hiện tại
        $courseIds = DB::table('courses')
            ->where($ownerCol, $teacherId)
            ->pluck('id')
            ->all();

        $selectedCourseId = $request->query('course_id');
        $attempts = collect();

        if (Schema::hasTable('quiz_attempts') && !empty($courseIds)) {
            $attemptCols = Schema::getColumnListing('quiz_attempts');
            $studentCol = in_array('user_id', $attemptCols) ? 'user_id' : 'student_id';

            $query = DB::table('quiz_attempts as qa')
                ->join('courses as c', 'c.id', '=', 'qa.course_id')
                ->leftJoin('users as u', 'u.id', '=', 'qa.' . $studentCol)
                ->whereIn('qa.course_id', $courseIds)
                ->whereNotNull('qa.submitted_at')
                ->select(
                    'qa.id',
                    'qa.score',
                    'qa.submitted_at',
                    'c.title as course_title',
                    'u.name as student_name',
                    'u.email as student_email'
                )
                ->orderByDesc('qa.submitted_at');

            if ($selectedCourseId) {
                $query->where('qa.course_id', (int) $selectedCourseId);
            }

            $attempts = $query->get();
        }

        $fileName = 'ket-qua-thi-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($attempts) {
            $handle = fopen('php://output', 'w');

            // Chèn UTF-8 BOM để Microsoft Excel hiển thị chuẩn tiếng Việt có dấu
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'STT',
                'Học viên',
                'Email',
                'Khóa học',
                'Điểm số (thang 10)',
                'Kết quả',
                'Thời gian nộp',
            ]);

            foreach ($attempts as $index => $item) {
                $score = (float) ($item->score ?? 0);
                fputcsv($handle, [
                    $index + 1,
                    $item->student_name ?? 'Học viên',
                    $item->student_email ?? '',
                    $item->course_title ?? '',
                    number_format($score, 2, '.', ''),
                    $score >= 5.0 ? 'Đạt' : 'Chưa đạt',
                    \Carbon\Carbon::parse($item->submitted_at)->format('d/m/Y H:i:s'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}