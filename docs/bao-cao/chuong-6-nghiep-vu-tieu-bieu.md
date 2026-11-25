# CHƯƠNG 6. MẪU THIẾT KẾ VÀ NGHIỆP VỤ TIÊU BIỂU

## 6.1. Các mẫu thiết kế (Design Patterns) áp dụng
1. **Service Layer Pattern:** Tách biệt logic nghiệp vụ phức tạp ra khỏi Controller (điển hình là `QuizService` và `AuthService`), giúp tái sử dụng mã nguồn và kiểm thử đơn vị (Unit Test) thuận lợi bằng PHPUnit.
2. **Repository / Eloquent ORM Pattern:** Trừu tượng hóa truy vấn cơ sở dữ liệu thông qua các Model (`User`, `Course`, `QuizAttempt`, `Question`).
3. **Middleware (Chain of Responsibility Pattern):** Xử lý lọc tuần tự các yêu cầu HTTP qua chuỗi kiểm tra phiên đăng nhập và vai trò người dùng trước khi chạm tới tầng xử lý nghiệp vụ.

## 6.2. Nghiệp vụ tiêu biểu: Chấm trắc nghiệm ở server, giới hạn thời gian và số lượt (`HT-06` & `HT-NFR1`)

### 6.2.1. Phân tích bài toán và ràng buộc bảo mật
Để đảm bảo tính công bằng và chống gian lận trong thi trực tuyến, hệ thống áp dụng 3 nguyên tắc xử lý hoàn toàn ở phía máy chủ (Server-side):
- **Chống lộ đáp án:** API/View tải đề thi chỉ gửi nội dung câu hỏi và 4 phương án A, B, C, D; tuyệt đối loại bỏ trường `correct_option` khỏi dữ liệu trả về trình duyệt trước khi nộp bài.
- **Giới hạn số lượt thi (`max_attempts = 3`):** Mỗi học viên chỉ được phép hoàn thành tối đa 3 lượt làm bài cho một khóa học. Hệ thống ghi nhận điểm cao nhất (`bestScore`) trong các lượt đã nộp.
- **Giới hạn thời gian làm bài (`quiz_minutes`):** Thời điểm bắt đầu (`started_at`) được lưu trên server ngay khi khởi tạo lượt thi. Khi nộp bài, server kiểm tra `now() <= started_at + quiz_minutes + 30s` (30 giây ân hạn độ trễ mạng) để từ chối các bài nộp quá hạn.

### 6.2.2. Mã giả thuật toán (Pseudocode)

**Thuật toán 1: Khởi tạo lượt làm bài (`QuizService::start`)**
```text
FUNCTION startQuiz(userId, courseId):
    questions <- getQuestionsByCourse(courseId)
    IF questions IS EMPTY THEN
        THROW Exception("Khóa học chưa có câu hỏi")
    END IF

    activeAttempt <- findUnfinishedAttempt(userId, courseId)
    IF activeAttempt != NULL THEN
        deadline <- activeAttempt.started_at + course.quiz_minutes
        IF currentTime() >= deadline THEN
            updateAttempt(activeAttempt.id, score = 0, submitted_at = deadline)
            activeAttempt <- NULL
        END IF
    END IF

    submittedCount <- countSubmittedAttempts(userId, courseId)
    IF activeAttempt != NULL THEN
        RETURN activeAttempt
    END IF

    IF submittedCount >= 3 THEN
        THROW Exception("Đã sử dụng hết 3/3 lượt làm bài")
    END IF

    newAttempt <- createAttempt(userId, courseId, started_at = currentTime())
    RETURN newAttempt
END FUNCTION

Thuật toán 2: Nộp bài và chấm điểm tự động (QuizService::submit)

FUNCTION submitQuiz(attemptId, userId, studentAnswers):
    attempt <- findAttemptById(attemptId)
    IF attempt == NULL OR attempt.user_id != userId THEN
        THROW Exception("Không có quyền nộp lượt thi này")
    END IF
    IF attempt.submitted_at != NULL THEN
        THROW Exception("Bài thi đã được nộp trước đó")
    END IF

    deadline <- attempt.started_at + course.quiz_minutes + 30_SECONDS
    IF currentTime() > deadline THEN
        THROW Exception("Đã quá thời gian làm bài, bài nộp bị từ chối")
    END IF

    questions <- getQuestionsByCourse(attempt.course_id)
    correctCount <- 0
    FOR EACH q IN questions DO
        chosen <- normalize(studentAnswers[q.id])
        IF chosen != "" AND chosen == normalize(q.correct_option) THEN
            correctCount <- correctCount + 1
        END IF
    END FOR

    score <- round((correctCount / length(questions)) * 10, 2)
    updateAttempt(attemptId, score, correctCount, submitted_at = currentTime())
    RETURN { score, correctCount, total: length(questions) }
END FUNCTION