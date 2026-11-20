<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả trắc nghiệm - {{ $course->title ?? 'Khóa học' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-body text-center py-4">
            <h4 class="mb-2">Kết quả bài trắc nghiệm: {{ $course->title ?? '' }}</h4>
            <div class="display-4 fw-bold text-primary my-2">{{ number_format($attempt->score, 2) }} / 10</div>
            <p class="mb-3 text-muted">
                Điểm cao nhất: <strong>{{ number_format($best_score, 2) }}/10</strong> |
                Đã dùng: <strong>{{ $used_attempts }}/3 lượt</strong> |
                Số lượt còn lại: <strong class="text-success">{{ $remaining_attempts }} lượt</strong>
            </p>

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ url('/courses/' . $course->id) }}" class="btn btn-outline-secondary">Về trang khóa học</a>
                @if($remaining_attempts > 0)
                    <a href="{{ route('student.quizzes.show', $course->id) }}" class="btn btn-primary">
                        Làm lại bài (Còn {{ $remaining_attempts }} lượt)
                    </a>
                @else
                    <button class="btn btn-secondary" disabled>Đã hết lượt làm bài</button>
                @endif
            </div>
        </div>
    </div>

    <h5 class="mb-3">Chi tiết đáp án đúng</h5>
    @foreach($questions as $index => $q)
        @php
            $chosen = strtoupper((string) ($user_answers[$q->id] ?? ''));
            $correct = strtoupper((string) $q->correct_option);
        @endphp
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <p class="fw-bold mb-2">
                    Câu {{ $index + 1 }}: {{ $q->question_text }}
                    <span class="badge bg-success ms-2">Đáp án đúng: {{ $correct }}</span>
                    @if($chosen !== '')
                        <span class="badge {{ $chosen === $correct ? 'bg-primary' : 'bg-danger' }} ms-1">
                            Bạn chọn: {{ $chosen }}
                        </span>
                    @endif
                </p>
                <ul class="list-group list-group-flush">
                    @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $text)
                        <li class="list-group-item {{ $key === $correct ? 'list-group-item-success fw-semibold' : ($key === $chosen ? 'list-group-item-danger' : '') }}">
                            <strong>{{ $key }}.</strong> {{ $text }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>
</body>
</html>