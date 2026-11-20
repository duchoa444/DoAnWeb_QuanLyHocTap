<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Kiểm tra giảng viên hiện tại có phải chủ sở hữu khóa học không
     */
    private function authorizeCourseOwner(Course $course): void
    {
        // Đổi 'teacher_id' thành 'user_id' nếu bảng courses dùng cột user_id
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa câu hỏi của khóa học này.');
        }
    }

    /**
     * Hiển thị danh sách câu hỏi và cấu hình thời gian làm bài của khóa học
     */
    public function index(Course $course): View
    {
        $this->authorizeCourseOwner($course);
        $questions = $course->questions()->latest()->get();

        return view('teacher.questions.index', compact('course', 'questions'));
    }

    /**
     * Cập nhật thời gian làm bài (phút) cho khóa học
     */
    public function updateDuration(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourseOwner($course);

        $validated = $request->validate([
            'quiz_minutes' => ['required', 'integer', 'min:1', 'max:180'],
        ], [
            'quiz_minutes.required' => 'Vui lòng nhập thời gian làm bài.',
            'quiz_minutes.integer'  => 'Thời gian làm bài phải là số nguyên.',
            'quiz_minutes.min'      => 'Thời gian làm bài tối thiểu là 1 phút.',
            'quiz_minutes.max'      => 'Thời gian làm bài tối đa là 180 phút.',
        ]);

        $course->update(['quiz_minutes' => $validated['quiz_minutes']]);

        return back()->with('success', 'Đã cập nhật thời gian làm bài thành công.');
    }

    /**
     * Thêm câu hỏi mới (4 lựa chọn, 1 đáp án đúng)
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourseOwner($course);

        $validated = $request->validate([
            'content'        => ['required', 'string', 'max:1000'],
            'option_a'       => ['required', 'string', 'max:255'],
            'option_b'       => ['required', 'string', 'max:255'],
            'option_c'       => ['required', 'string', 'max:255'],
            'option_d'       => ['required', 'string', 'max:255'],
            'correct_option' => ['required', 'in:A,B,C,D'],
        ], [
            'required'          => 'Trường này không được để trống.',
            'correct_option.in' => 'Đáp án đúng phải thuộc một trong 4 lựa chọn A, B, C hoặc D.',
        ]);

        $course->questions()->create($validated);

        return back()->with('success', 'Đã thêm câu hỏi mới.');
    }

    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, Course $course, Question $question): RedirectResponse
    {
        $this->authorizeCourseOwner($course);
        abort_if($question->course_id !== $course->id, 404);

        $validated = $request->validate([
            'content'        => ['required', 'string', 'max:1000'],
            'option_a'       => ['required', 'string', 'max:255'],
            'option_b'       => ['required', 'string', 'max:255'],
            'option_c'       => ['required', 'string', 'max:255'],
            'option_d'       => ['required', 'string', 'max:255'],
            'correct_option' => ['required', 'in:A,B,C,D'],
        ]);

        $question->update($validated);

        return back()->with('success', 'Đã cập nhật câu hỏi.');
    }

    /**
     * Xóa câu hỏi
     */
    public function destroy(Course $course, Question $question): RedirectResponse
    {
        $this->authorizeCourseOwner($course);
        abort_if($question->course_id !== $course->id, 404);

        $question->delete();

        return back()->with('success', 'Đã xóa câu hỏi.');
    }
}