<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Làm bài trắc nghiệm - {{ $course->title ?? 'Khóa học' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="card shadow-sm mb-4 sticky-top" style="top: 10px; z-index: 1000;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">{{ $course->title ?? 'Bài kiểm tra trắc nghiệm' }}</h5>
                <span class="badge bg-primary">Lần làm thứ {{ $attempt_no }} / 3</span>
                <span class="badge bg-secondary">Tổng số câu: {{ $questions->count() }}</span>
            </div>
            <div class="text-end">
                <div class="small text-muted">Thời gian còn lại</div>
                <div id="countdown" class="fs-4 fw-bold text-danger">--:--</div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="quizForm" action="{{ route('student.quizzes.submit', $attempt->id) }}" method="POST">
        @csrf
        @foreach($questions as $index => $q)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <p class="fw-bold mb-3">Câu {{ $index + 1 }}: {{ $q->question_text }}</p>
                    @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $text)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="answers[{{ $q->id }}]"
                                   id="q{{ $q->id }}_{{ $key }}"
                                   value="{{ $key }}">
                            <label class="form-check-label" for="q{{ $q->id }}_{{ $key }}">
                                <strong>{{ $key }}.</strong> {{ $text }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
            <a href="{{ url('/courses/' . $course->id) }}" class="btn btn-outline-secondary">Quay lại khóa học</a>
            <button type="submit" class="btn btn-success btn-lg px-5"
                    onclick="return confirm('Bạn có chắc chắn muốn nộp bài ngay bây giờ?')">
                Nộp bài
            </button>
        </div>
    </form>
</div>

<script>
    // Đồng bộ thời gian kết thúc theo started_at từ Server (chỉ hiển thị; Server quyết định hạn nộp)
    const startedAtMs = new Date("{{ \Carbon\Carbon::parse($attempt->started_at)->toIso8601String() }}").getTime();
    const durationMs = {{ (int) $quiz_minutes }} * 60 * 1000;
    const endTimeMs = startedAtMs + durationMs;

    const countdownEl = document.getElementById('countdown');
    const quizForm = document.getElementById('quizForm');
    let submitted = false;

    function updateTimer() {
        const nowMs = Date.now();
        const diff = endTimeMs - nowMs;

        if (diff <= 0) {
            countdownEl.textContent = "00:00";
            if (!submitted) {
                submitted = true;
                quizForm.submit();
            }
            return;
        }

        const totalSeconds = Math.floor(diff / 1000);
        const mins = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
        const secs = String(totalSeconds % 60).padStart(2, '0');
        countdownEl.textContent = `${mins}:${secs}`;
    }

    updateTimer();
    setInterval(updateTimer, 1000);
</script>
</body>
</html>