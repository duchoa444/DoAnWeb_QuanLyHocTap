# Nhật ký sử dụng AI (AI Log) – Thành viên 2 (TV2)
**Dự án:** Hệ thống Quản lý Học tập Trực tuyến (`DoAnWeb_QuanLyHocTap`)
**Tính năng phụ trách chính:** `HT-06` – Làm bài & chấm điểm trắc nghiệm tự động
**Nhánh Git:** `feature/HT-06-trac-nghiem`

---

## Tuần 1: Phân tích Đặc tả, Thiết kế Hệ thống & Cơ sở Dữ liệu

### Ngày 1: Khởi tạo môi trường, Git Workflow & Phân tích yêu cầu
- **Mục tiêu:** Thiết lập môi trường phát triển (XAMPP, PHP 8.x, Laravel, MySQL), đồng bộ nhánh Git nhóm và phân rã yêu cầu chức năng/phi chức năng cho module Trắc nghiệm (`HT-06`).
- **Nội dung hỗ trợ từ AI:**
  - Gợi ý quy trình phân nhánh Git chuẩn (`main` -> `develop` -> `feature/HT-06-trac-nghiem`) và quy ước viết commit message theo chuẩn Conventional Commits kèm cờ `AI-assisted`.
  - Hỗ trợ phân tích các ràng buộc nghiệp vụ cốt lõi từ tài liệu đặc tả: giới hạn tối đa 3 lượt làm bài (`HT-06`), chống lộ đáp án đúng xuống Client và kiểm soát thời gian nộp bài ở Server (`HT-NFR1`), kiểm tra điều kiện ghi danh trước khi thi (`HT-NFR2`).
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự cấu hình file `.env` kết nối cơ sở dữ liệu MySQL cục bộ (`DoAn_QuanLyHocTap`) và kiểm tra quyền truy cập kho lưu trữ GitHub của nhóm.
  - Đối chiếu danh sách yêu cầu với các thành viên còn lại (đặc biệt là module Khóa học và Ghi danh) để thống nhất đầu vào/đầu ra dữ liệu.

### Ngày 2: Thiết kế luồng nghiệp vụ & Mô hình hóa dữ liệu (ERD)
- **Mục tiêu:** Thiết kế luồng xử lý chi tiết (Sequence/Activity flow) cho quá trình làm bài trắc nghiệm và chốt cấu trúc các bảng liên quan.
- **Nội dung hỗ trợ từ AI:**
  - Đề xuất cấu trúc 2 bảng chính phục vụ trắc nghiệm: `questions` (lưu nội dung câu hỏi, 4 lựa chọn `option_a..d`, `correct_option`) và `quiz_attempts` (lưu `user_id`, `course_id`, `started_at`, `submitted_at`, `score`).
  - Tư vấn bổ sung cột cấu hình thời gian làm bài `quiz_minutes` vào bảng `courses` thay vì gán cứng (hardcode) 15 phút để giảng viên có thể linh hoạt điều chỉnh theo từng khóa học.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự rà soát các khóa ngoại (`course_id`, `user_id`) và bổ sung cơ chế xóa tầng (`onDelete('cascade')`) nhằm tránh dữ liệu rác khi khóa học hoặc người dùng bị xóa.
  - Thống nhất với nhóm tên các cột khóa ngoại chuẩn trong bảng `courses` (`category_id`, `teacher_id`) để đảm bảo đồng bộ toàn dự án.

### Ngày 3: Xây dựng Migration & Kiểm thử cấu trúc CSDL
- **Mục tiêu:** Viết các file migration tạo bảng `questions`, `quiz_attempts` và cập nhật bảng `courses`, chạy kiểm thử trên MySQL.
- **Nội dung hỗ trợ từ AI:**
  - Sinh cú pháp migration Laravel cho bảng `questions` (dùng kiểu `enum` hoặc `char(1)` cho `correct_option` giới hạn trong `A, B, C, D`) và bảng `quiz_attempts` (cho phép `submitted_at` và `score` nhận giá trị `nullable` khi học viên mới bắt đầu làm bài).
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự bổ sung đánh chỉ mục (`index`) cho cặp cột `(user_id, course_id)` trên bảng `quiz_attempts` để tối ưu tốc độ truy vấn đếm số lượt làm bài và tìm lượt làm dở.
  - Chạy `php artisan migrate:fresh` và kiểm tra trực tiếp cấu trúc bảng, kiểu dữ liệu, ràng buộc trên phpMyAdmin.

### Ngày 4: Xây dựng Seeder dữ liệu mẫu & Thiết kế giao diện khung (Wireframe/Blade)
- **Mục tiêu:** Tạo dữ liệu câu hỏi trắc nghiệm mẫu để phục vụ kiểm thử và dựng giao diện trang làm bài / xem kết quả.
- **Nội dung hỗ trợ từ AI:**
  - Tạo mẫu `QuestionSeeder` sinh bộ câu hỏi trắc nghiệm mẫu gắn với các khóa học đang ở trạng thái `published`.
  - Gợi ý bố cục giao diện trang làm bài (đồng hồ đếm ngược JavaScript đồng bộ theo mốc `started_at` từ Server, danh sách câu hỏi radio button và cảnh báo trước khi nộp bài).
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự điều chỉnh lại logic đồng hồ đếm ngược trên giao diện để tự động kích hoạt gửi form (`submit`) khi hết giờ, đồng thời vẫn giữ chốt chặn kiểm tra thời gian thực tế tại Server để chống gian lận từ trình duyệt.

### Ngày 5: Rà soát tích hợp cuối Tuần 1 & Đồng bộ nhánh `develop`
- **Mục tiêu:** Kiểm tra chéo schema với các thành viên trong nhóm, xử lý xung đột (conflict) và cập nhật tiến độ Tuần 1 trên Trello.
- **Nội dung hỗ trợ từ AI:**
  - Hỗ trợ kiểm tra và giải quyết xung đột thứ tự thời gian (timestamp) giữa các file migration của các thành viên khi gộp nhánh về `develop`.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Chạy lại toàn bộ migration và seeder từ đầu sau khi kéo code mới nhất từ `origin/develop`, đảm bảo không phát sinh lỗi khóa ngoại hay thiếu bảng.

---

## Tuần 2: Phát triển Logic Nghiệp vụ & Kiểm thử Tự động

### Ngày 6: Xây dựng Service xử lý Trắc nghiệm (`QuizService`)
- **Mục tiêu:** Xây dựng tầng nghiệp vụ xử lý bắt đầu làm bài (`start`) và nộp bài (`submit`) hoàn toàn tại Server theo đúng đặc tả (`HT-06`, `HT-NFR1`, `HT-NFR2`).
- **Nội dung hỗ trợ từ AI:**
  - Sinh cấu trúc khung `QuizService` với hai phương thức `start()` và `submit()` bọc trong `DB::transaction` và sử dụng `lockForUpdate()` để chống xung đột (race condition) khi học viên bấm gửi nhiều lần.
  - Gợi ý truy vấn tách cột `select('id', 'course_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d')` khi lấy đề thi để ngăn rò rỉ `correct_option` xuống Client (`HT-NFR1`).
  - Tính toán hạn nộp tại Server: `started_at + quiz_minutes + 1 phút dung sai` và giới hạn tối đa 3 lượt làm bài (`HT-06`).
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự rà soát và bổ sung logic khôi phục lượt làm dở (chưa nộp và còn hạn) khi học viên tải lại trang (`F5`), tránh làm mất lượt oan của người dùng.
  - Tự bổ sung cơ chế tự động chốt `0` điểm cho lượt làm bài cũ nếu đã quá hạn mà chưa nộp trước khi mở lượt mới.
  - Rà soát thông báo lỗi tiếng Việt và mã trạng thái HTTP (`403`, `404`, `422`) khớp với quy ước chung của nhóm.

### Ngày 7: Viết Unit Test & Tái cấu trúc Query Builder độc lập
- **Mục tiêu:** Hoàn thiện tối thiểu 2 Unit Test cho `QuizService` và đảm bảo toàn bộ test chạy `PASS` độc lập trên môi trường kiểm thử.
- **Nội dung hỗ trợ từ AI:**
  - Sinh khung file `tests/Unit/QuizServiceTest.php` sử dụng `RefreshDatabase` và viết 2 kịch bản kiểm thử cơ bản:
    1. Chấm điểm chính xác thang điểm 10 (`8/10` câu đúng $rightarrow$ `8.0` điểm).
    2. Từ chối bài nộp khi vượt quá thời hạn cho phép cộng 1 phút dung sai (`started_at` lùi 17 phút cho đề 15 phút $rightarrow$ ném ngoại lệ từ chối nộp).
  - Hỗ trợ chuyển đổi truy vấn trong `QuizService` từ Eloquent Models (`Course`, `Enrollment`, `Question`, `QuizAttempt`) sang `DB::table(...)` để không bị lỗi thiếu class Model do các thành viên khác đang phát triển song song.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự kiểm tra schema migration thực tế của nhóm (`categories`, `courses` dùng khóa ngoại `category_id` và `teacher_id`) để điều chỉnh hàm `seedQuizData()` trong file test khớp 100% với cấu trúc bảng.
  - Tự viết thêm **test case thứ 3** (`test_bo_trong_cau_va_dap_an_khong_hop_le`): kiểm chứng trường hợp học viên bỏ trống câu hỏi hoặc gửi ký tự đáp án không hợp lệ (`'Z'`, `''`) thì hệ thống không báo lỗi mà tính câu đó sai (`1/4` câu đúng $rightarrow$ `2.5` điểm).
  - Xử lý lỗi cú pháp ký tự thoát (`\$`) phát sinh khi sao chép mã nguồn và chạy xác nhận **`3 passed (7 assertions)`** trên PHPUnit.

  ### Ngày 9: Kiểm chứng bảo mật bài thi (`HT-NFR1`) & Rà soát tích hợp Mốc 2
- **Mục tiêu:** Kiểm chứng toàn diện các quy tắc chống gian lận của module Trắc nghiệm (`HT-NFR1`, `HT-06`) và hỗ trợ review PR `HT-07` trước hạn chốt Mốc 2 (21:00).
- **Nội dung hỗ trợ từ AI:**
  - Lập kịch bản kiểm thử lỗ hổng bảo mật phía Client/Network: kiểm tra payload phản hồi trên DevTools Network, giả lập chỉnh lùi đồng hồ hệ thống để qua mặt bộ đếm giờ JavaScript, và gửi request vượt quá 3 lượt thi.
  - Rà soát an toàn kiểu dữ liệu đầu vào (`answers[]`) trong `QuizController` nhằm ngăn chặn lỗi ngoại lệ khi kẻ xấu chỉnh sửa tham số request.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Trực tiếp mở tab Network trên trình duyệt khi làm bài `/courses/{id}/quiz`: xác nhận response HTML không chứa trường `correct_option` trước khi nộp bài (`HT-NFR1` đạt).
  - Thử chỉnh lùi đồng hồ Windows 30 phút và nộp bài sau thời hạn thực tế của đề: xác nhận Server từ chối nộp bài thành công.
  - Thử mở lượt làm bài thứ 4: xác nhận hệ thống chặn đúng quy định tối đa 3 lượt.
  - Tham gia review code PR `feature/HT-07-ho-so` (truy vấn lịch sử điểm từ `quiz_attempts`).