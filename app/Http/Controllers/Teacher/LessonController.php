<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\LessonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LessonController extends Controller
{
    public function __construct(
        protected LessonService $lessonService
    ) {}

    // Kiểm tra khóa học có thuộc giảng viên đang đăng nhập không
    private function checkCourseOwnership(int $courseId): Course
    {
        return Course::query()
            ->where('teacher_id', Auth::id())
            ->findOrFail($courseId);
    }

    // Kiểm tra URL YouTube và mã video
    private function isValidYouTubeUrl(string $url): bool
    {
        $parts = parse_url($url);

        if (!$parts || empty($parts['host'])) {
            return false;
        }

        $host = strtolower($parts['host']);

        $allowedHosts = [
            'youtube.com',
            'www.youtube.com',
            'm.youtube.com',
            'youtu.be',
        ];

        if (!in_array($host, $allowedHosts, true)) {
            return false;
        }

        $path = $parts['path'] ?? '';
        $videoId = '';

        if ($host === 'youtu.be') {
            $videoId = trim($path, '/');
        } elseif ($path === '/watch') {
            parse_str($parts['query'] ?? '', $query);
            $videoId = $query['v'] ?? '';
        } elseif (preg_match(
            '#^/(shorts|embed|live)/([A-Za-z0-9_-]{11})$#',
            $path,
            $matches
        )) {
            $videoId = $matches[2];
        }

        return (bool) preg_match(
            '/^[A-Za-z0-9_-]{11}$/',
            $videoId
        );
    }

    // Quy tắc kiểm tra URL YouTube
    private function videoUrlRules(): array
    {
        return [
            'nullable',
            'url',
            function ($attribute, $value, $fail) {
                if (!$this->isValidYouTubeUrl($value)) {
                    $fail('Vui lòng nhập đường dẫn video YouTube hợp lệ.');
                }
            },
        ];
    }

    // Danh sách bài học
    public function index(int $courseId)
    {
        $course = $this->checkCourseOwnership($courseId);

        $lessons = $this->lessonService->getTeacherLessonsList(
            $courseId,
            Auth::id()
        );

        return view(
            'teacher.lessons.index',
            compact('course', 'lessons')
        );
    }

    // Form thêm bài học
    public function create(int $courseId)
    {
        $course = $this->checkCourseOwnership($courseId);

        return view('teacher.lessons.create', compact('course'));
    }

    // Lưu bài học mới
    public function store(Request $request, int $courseId)
    {
        $this->checkCourseOwnership($courseId);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => $this->videoUrlRules(),
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $pdfPath = null;

        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store(
                'lessons',
                'local'
            );

            if (!$pdfPath) {
                throw ValidationException::withMessages([
                    'pdf' => 'Không thể lưu tệp PDF. Vui lòng thử lại.',
                ]);
            }
        }

        try {
            $data['pdf_path'] = $pdfPath;

            unset($data['pdf']);

            $this->lessonService->createTeacherLesson(
                $courseId,
                Auth::id(),
                $data
            );
        } catch (\Throwable $e) {
            if ($pdfPath) {
                Storage::disk('local')->delete($pdfPath);
            }

            throw $e;
        }

        return redirect()
            ->route('teacher.lessons.index', $courseId)
            ->with('success', 'Thêm bài học thành công!');
    }

    // Form chỉnh sửa bài học
    public function edit(int $courseId, int $lessonId)
    {
        $course = $this->checkCourseOwnership($courseId);

        $lesson = $this->lessonService->findTeacherLesson(
            $lessonId,
            $courseId,
            Auth::id()
        );

        return view(
            'teacher.lessons.edit',
            compact('course', 'lesson')
        );
    }

    // Cập nhật bài học
    public function update(Request $request, int $courseId, int $lessonId)
    {
        $this->checkCourseOwnership($courseId);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => $this->videoUrlRules(),
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $lesson = $this->lessonService->findTeacherLesson(
            $lessonId,
            $courseId,
            Auth::id()
        );

        $oldPdfPath = $lesson->pdf_path;
        $newPdfPath = null;

        if ($request->hasFile('pdf')) {
            $newPdfPath = $request->file('pdf')->store(
                'lessons',
                'local'
            );

            if (!$newPdfPath) {
                throw ValidationException::withMessages([
                    'pdf' => 'Không thể lưu tệp PDF mới. Vui lòng thử lại.',
                ]);
            }

            $data['pdf_path'] = $newPdfPath;
        }

        try {
            unset($data['pdf']);

            $this->lessonService->updateTeacherLesson(
                $lessonId,
                $courseId,
                Auth::id(),
                $data
            );
        } catch (\Throwable $e) {
            if ($newPdfPath) {
                Storage::disk('local')->delete($newPdfPath);
            }

            throw $e;
        }

        // Chỉ xóa PDF cũ sau khi cập nhật thành công
        if ($newPdfPath && $oldPdfPath) {
            Storage::disk('local')->delete($oldPdfPath);
        }

        return redirect()
            ->route('teacher.lessons.index', $courseId)
            ->with('success', 'Cập nhật bài học thành công!');
    }

    // Xóa bài học
    public function destroy(int $courseId, int $lessonId)
    {
        $this->checkCourseOwnership($courseId);

        $lesson = $this->lessonService->findTeacherLesson(
            $lessonId,
            $courseId,
            Auth::id()
        );

        $pdfPath = $lesson->pdf_path;

        $this->lessonService->deleteTeacherLesson(
            $lessonId,
            $courseId,
            Auth::id()
        );

        // Xóa PDF sau khi xóa bài học thành công
        if ($pdfPath) {
            Storage::disk('local')->delete($pdfPath);
        }

        return redirect()
            ->route('teacher.lessons.index', $courseId)
            ->with('success', 'Xóa bài học thành công!');
    }

    // Tải tài liệu PDF
    public function downloadPdf(int $courseId, int $lessonId)
    {
        $this->checkCourseOwnership($courseId);

        $lesson = $this->lessonService->findTeacherLesson(
            $lessonId,
            $courseId,
            Auth::id()
        );

        if (
            !$lesson->pdf_path ||
            !Storage::disk('local')->exists($lesson->pdf_path)
        ) {
            abort(404, 'Không tìm thấy tệp PDF.');
        }

        return Storage::disk('local')->download(
            $lesson->pdf_path,
            'tai-lieu-bai-hoc.pdf'
        );
    }

    // Di chuyển bài học lên hoặc xuống
    public function move(Request $request, int $courseId, int $lessonId)
    {
        $this->checkCourseOwnership($courseId);

        $data = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $this->lessonService->moveTeacherLesson(
            $lessonId,
            $courseId,
            Auth::id(),
            $data['direction']
        );

        return back()->with(
            'success',
            'Đã thay đổi thứ tự bài học!'
        );
    }
}