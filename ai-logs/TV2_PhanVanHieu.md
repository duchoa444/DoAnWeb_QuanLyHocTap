# Nhật ký sử dụng AI (AI Log) – Thành viên 2 (TV2)
**Họ và tên:** Phan Văn Hiếu (`hieupv11`)  
**Vai trò:** Backend Lead (TV2)  
**Dự án:** Hệ thống Quản lý Học tập Trực tuyến (`DoAnWeb_QuanLyHocTap`)  
**Tính năng phụ trách chính:** `HT-03` (Đăng nhập, Đăng ký, Phân quyền) & `HT-06` (Làm bài & chấm điểm trắc nghiệm tự động)  
**Nhánh Git chính:** `feature/HT-06-trac-nghiem`  

---

## Tóm tắt Các phiên làm việc với AI (Tổng hợp Tuần 1 – Tuần 2)

| Phiên / Thời điểm | Chức năng | Nội dung chính sử dụng AI | Mức độ can thiệp thủ công |
| :--- | :--- | :--- | :--- |
| **Ngày 1 – 2** | `HT-03`, `HT-06` | Phân tích đặc tả, thiết kế ERD (`users`, `questions`, `quiz_attempts`) & quy chuẩn Git | Đối chiếu khóa ngoại (`category_id`, `teacher_id`) với nhóm |
| **Ngày 3 – 4** | `HT-03`, `HT-06` | Viết Migration, Seeder dữ liệu mẫu & khung xác thực đăng ký/đăng nhập | Thêm index `(user_id, course_id)`, kiểm thử DB trên phpMyAdmin |
| **Ngày 5** | `HT-03` | Hoàn thiện `AuthController`, Middleware phân quyền (`role:admin/teacher/student`) | Rà soát xung đột migration & kiểm thử phân luồng vai trò |
| **Ngày 6** | `HT-06` | Xây dựng `QuizService` (`start`, `submit`) dùng `DB::transaction` & `lockForUpdate` | Bổ sung khôi phục lượt làm dở khi F5 & tự chốt 0 điểm lượt quá hạn |
| **Ngày 7** | `HT-06` | Viết `QuizServiceTest` (PHPUnit) & chuyển đổi sang Query Builder (`DB::table`) | Tự viết thêm test case #3 (`1/4` điểm), sửa lỗi cú pháp & chạy PASS 3/3 |
| **Ngày 8** | `HT-06` | Xây dựng `QuizController` (`take`, `result`), `getResult()` & 2 trang Blade View | Đồng bộ route với `routes/web.php` nhóm & test luồng end-to-end |

---

## Tuần 1: Phân tích Đặc tả, Thiết kế Hệ thống & Xác thực (`HT-03`)

### Ngày 1: Khởi tạo môi trường, Git Workflow & Phân tích yêu cầu
- **Mục tiêu:** Thiết lập môi trường phát triển (XAMPP, PHP 8.x, Laravel, MySQL), đồng bộ nhánh Git nhóm và phân rã yêu cầu chức năng/phi chức năng cho hai module phụ trách (`HT-03` và `HT-06`).
- **Nội dung hỗ trợ từ AI:**
  - Gợi ý quy trình phân nhánh Git chuẩn (`main` -> `develop` -> `feature/...`) và quy ước viết commit message theo chuẩn Conventional Commits kèm cờ `AI-assisted`.
  - Hỗ trợ phân tích các ràng buộc nghiệp vụ cốt lõi từ tài liệu đặc tả: xác thực mật khẩu băm (`Hash`) và phân quyền 3 vai trò (`HT-03`), giới hạn tối đa 3 lượt làm bài (`HT-06`), chống lộ đáp án đúng xuống Client và kiểm soát thời gian nộp bài ở Server (`HT-NFR1`), kiểm tra điều kiện ghi danh trước khi thi (`HT-NFR2`).
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự cấu hình file `.env` kết nối cơ sở dữ liệu MySQL cục bộ (`quan_ly_hoc_tap`) và kiểm tra quyền truy cập kho lưu trữ GitHub của nhóm.
  - Đối chiếu danh sách yêu cầu với các thành viên còn lại (đặc biệt là module Khóa học và Ghi danh) để thống nhất đầu vào/đầu ra dữ liệu.

### Ngày 2: Thiết kế luồng nghiệp vụ & Mô hình hóa dữ liệu (ERD)
- **Mục tiêu:** Thiết kế luồng xử lý chi tiết cho quá trình đăng nhập/phân quyền (`HT-03`) và làm bài trắc nghiệm (`HT-06`), chốt cấu trúc các bảng liên quan.
- **Nội dung hỗ trợ từ AI:**
  - Đề xuất cấu trúc bảng `users` (bổ sung cột `role` và `is_active` phục vụ khóa/mở tài khoản) cùng 2 bảng chính phục vụ trắc nghiệm: `questions` (lưu nội dung câu hỏi, 4 lựa chọn `option_a..d`, `correct_option`) và `quiz_attempts` (lưu `user_id`, `course_id`, `started_at`, `submitted_at`, `score`).
  - Tư vấn bổ sung cột cấu hình thời gian làm bài `quiz_minutes` vào bảng `courses` thay vì gán cứng (hardcode) 15 phút để giảng viên có thể linh hoạt điều chỉnh theo từng khóa học.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự rà soát các khóa ngoại (`course_id`, `user_id`) và bổ sung cơ chế xóa tầng (`onDelete('cascade')`) nhằm tránh dữ liệu rác khi khóa học hoặc người dùng bị xóa.
  - Thống nhất với nhóm tên các cột khóa ngoại chuẩn trong bảng `courses` (`category_id`, `teacher_id`) để đảm bảo đồng bộ toàn dự án.

### Ngày 3: Xây dựng Migration & Kiểm thử cấu trúc CSDL
- **Mục tiêu:** Viết các file migration tạo bảng `users`, `questions`, `quiz_attempts` và cập nhật bảng `courses`, chạy kiểm thử trên MySQL.
- **Nội dung hỗ trợ từ AI:**
  - Sinh cú pháp migration Laravel cho bảng `questions` (giới hạn `correct_option` trong `A, B, C, D`) và bảng `quiz_attempts` (cho phép `submitted_at` và `score` nhận giá trị `nullable` khi học viên mới bắt đầu làm bài).
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự bổ sung đánh chỉ mục (`index`) cho cặp cột `(user_id, course_id)` trên bảng `quiz_attempts` để tối ưu tốc độ truy vấn đếm số lượt làm bài và tìm lượt làm dở.
  - Chạy `php artisan migrate:fresh` và kiểm tra trực tiếp cấu trúc bảng, kiểu dữ liệu, ràng buộc trên phpMyAdmin.

### Ngày 4: Xây dựng Seeder dữ liệu mẫu & Chức năng Đăng ký / Đăng nhập (`HT-03`)
- **Mục tiêu:** Tạo dữ liệu tài khoản mẫu, câu hỏi trắc nghiệm mẫu và hoàn thiện luồng đăng ký, đăng nhập, đăng xuất (`HT-03`).
- **Nội dung hỗ trợ từ AI:**
  - Sinh khung `AuthController` (xử lý `register`, `login`, `logout` với `Auth::attempt()`, `session()->regenerate()` và mã hóa mật khẩu `Hash::make`) cùng middleware kiểm tra vai trò (`EnsureUserHasRole`).
  - Tạo mẫu `QuestionSeeder` sinh bộ câu hỏi trắc nghiệm mẫu gắn với các khóa học đang ở trạng thái `published`.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Tự bổ sung điều kiện kiểm tra trạng thái hoạt động của tài khoản (`is_active`) khi đăng nhập để ngăn tài khoản bị Admin khóa truy cập hệ thống.
  - Kiểm thử thủ công đầy đủ các trường hợp: đăng ký trùng email, đăng nhập sai mật khẩu, điều hướng đúng dashboard theo từng vai trò (`admin`, `teacher`, `student`).

### Ngày 5: Rà soát tích hợp cuối Tuần 1 & Đồng bộ nhánh `develop`
- **Mục tiêu:** Kiểm tra chéo schema với các thành viên trong nhóm, xử lý xung đột (conflict) và hoàn tất thẻ `[HT-03]` trên Trello.
- **Nội dung hỗ trợ từ AI:**
  - Hỗ trợ kiểm tra và giải quyết xung đột thứ tự thời gian (timestamp) giữa các file migration của các thành viên khi gộp nhánh về `develop`.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Chạy lại toàn bộ migration và seeder từ đầu sau khi kéo code mới nhất từ `origin/develop`, đảm bảo không phát sinh lỗi khóa ngoại hay thiếu bảng.

---

## Tuần 2: Phát triển Logic Nghiệp vụ, Kiểm thử & Giao diện Trắc nghiệm (`HT-06`)

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
    1. Chấm điểm chính xác thang điểm 10 (`8/10` câu đúng $\srightarrow$ `8.0` điểm).
    2. Từ chối bài nộp khi vượt quá thời hạn cho phép cộng 1 phút dung sai (`started_at` lùi 17 phút cho đề 15 phút $\rightarrow$ ném ngoại lệ từ chối nộp).
  - Hỗ trợ chuyển đổi truy vấn trong `QuizService` từ Eloquent Models sang `DB::table(...)` để không bị lỗi thiếu class Model do các thành viên khác đang phát triển song song.
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
  - Tự viết thêm **test case thứ 3** (`test_bo_trong_cau_va_dap_an_khong_hop_le`): kiểm chứng trường hợp học viên bỏ trống câu hỏi hoặc gửi ký tự đáp án không hợp lệ (`'Z'`, `''`) thì hệ thống không báo lỗi mà tính câu đó sai (`1/4` câu đúng $\rightarrow$ `2.5` điểm).
  - Xử lý lỗi cú pháp ký tự thoát (`\$`) phát sinh khi sao chép mã nguồn và chạy xác nhận **`3 passed (7 assertions)`** trên PHPUnit.

### Ngày 8: Xây dựng Controller & Giao diện Làm bài / Kết quả Trắc nghiệm (`HT-06`)
- **Mục tiêu:** Hoàn thiện luồng làm bài trắc nghiệm đầu cuối (end-to-end) gồm trang làm bài có đồng hồ đếm ngược và trang kết quả hiển thị điểm, đáp án đúng, số lượt còn lại.
- **Nội dung hỗ trợ từ AI:**
  - Bổ sung phương thức `QuizService::getResult()` chỉ trả về cột `correct_option` khi lượt làm bài đã có trạng thái nộp (`submitted_at != null`) và đúng chủ sở hữu (`user_id`), kết hợp tính toán `used_attempts`, `remaining_attempts` và `best_score`.
  - Xây dựng `QuizController` (`start`, `take`, `submit`, `result`) và 2 giao diện Blade (`resources/views/student/quiz/take.blade.php` và `result.blade.php`) với đồng hồ đếm ngược JavaScript đồng bộ từ mốc `started_at` của Server.
- **Kiểm chứng & Chỉnh sửa thủ công:**
  - Đối chiếu file `routes/web.php` hiện có của nhóm và bổ sung 2 route GET (`quiz.take`, `quiz.result`) mà không làm thay đổi tên route `POST` (`quiz.start`, `quiz.submit`) đã gắn sẵn.
  - Chạy kiểm thử thực tế trên trình duyệt toàn bộ luồng: đăng nhập học viên $\rightarrow$ vào làm bài $\rightarrow$ kiểm tra mã nguồn HTML không lộ đáp án $\rightarrow$ nộp bài $\rightarrow$ xem điểm, đáp án đúng và số lượt còn lại $\rightarrow$ kiểm tra chặn ở lượt thứ 4.

## Ngày 10 – Thứ 6, tuần 2 (Mốc 2 – Demo & Unit Test HT-06)
- **Mục tiêu:** Demo Mốc 2 tính năng `HT-06` và viết ≥ 2 Unit Test cho `QuizService` trên nhánh `test/HT-06` (chặn khi hết 3 lượt làm bài và lấy điểm cao nhất).
- **Sử dụng AI:**
  - *Prompt:* Nhờ AI hỗ trợ hoàn thiện bộ Unit Test PHPUnit (`tests/Unit/QuizServiceTest.php`) kiểm tra kịch bản chặn học viên khi làm đủ 3 lượt (`test_het_3_luot_lam_bai_bi_chan`), truy xuất điểm cao nhất (`test_lay_diem_cao_nhat_qua_cac_lan_lam`) và kiểm tra ràng buộc quá thời gian nộp bài trong `QuizService.php`.
  - *Kiểm chứng & Chỉnh sửa:* Tự đối chiếu tên phương thức (`start`, `submit`, `getResult`) và cấu trúc dữ liệu của `QuizService.php`, chạy lệnh `php artisan test --filter=QuizServiceTest` xác nhận toàn bộ 5/5 test case (13 assertions) đạt trạng thái PASS.

  ## Ngày 12 – Thứ 3, tuần 3 (Hoàn tất sửa lỗi & Viết bản nháp Chương 5, Chương 6)
- **Mục tiêu:** Đảm bảo 0 lỗi tồn đọng ở phần `HT-03`, `HT-06`, viết bản nháp Chương 5 (Kiến trúc hệ thống, phân quyền 3 vai trò) và Chương 6 (Mẫu thiết kế, mã giả và độ phức tạp thuật toán chấm trắc nghiệm) trên nhánh `docs/bao-cao-c5-c6`.
- **Sử dụng AI:**
  - *Prompt:* Nhờ AI hỗ trợ hệ thống hóa cấu trúc Chương 5 (mô hình MVC + Service Layer, cơ chế RBAC 3 vai trò) và chuẩn hóa mã giả (Pseudocode) cùng đánh giá độ phức tạp thời gian/không gian $O(N)$ cho thuật toán khởi tạo và chấm điểm trắc nghiệm trong `QuizService.php`.
  - *Kiểm chứng & Chỉnh sửa:* Đối chiếu trực tiếp từng dòng mã giả với mã nguồn thực tế trong `app/Services/QuizService.php` và `app/Services/AuthService.php` để đảm bảo các điều kiện biên (30 giây ân hạn nộp bài, chặn quá 3 lượt thi, băm mật khẩu) khớp 100% với hệ thống đang chạy.

---

## Phản tư quá trình sử dụng AI (AI Reflection – TV2)

### 1. Hai trường hợp AI hỗ trợ hiệu quả (Tốt)
1. **Sinh khung Unit Test PHPUnit (`QuizServiceTest.php`):** Khi cung cấp đặc tả nghiệp vụ của `HT-06` (chấm điểm thang 10, chặn quá 3 lượt thi, lấy điểm cao nhất `bestScore`), AI nhanh chóng tạo ra cấu trúc các hàm test chuẩn cú pháp PHPUnit và `RefreshDatabase`, giúp tiết kiệm khoảng 60% thời gian viết mã kiểm thử lặp lại.
2. **Hệ thống hóa mã giả (Pseudocode) và phân tích độ phức tạp cho Chương 6:** AI chuyển đổi tốt logic xử lý từ mã nguồn PHP (`QuizService::start` và `QuizService::submit`) sang dạng mã giả chuẩn thuật toán và phân tích rõ độ phức tạp $O(N)$, giúp phần trình bày báo cáo mạch lạc, khoa học.

### 2. Hai trường hợp AI đưa ra kết quả chưa chính xác cần tự sửa (Sai)
1. **Giả định sai tên phương thức và cột dữ liệu khi sinh Unit Test lần đầu:** AI tự đặt tên hàm là `startAttempt()` và `getHighestScore()` cùng cột `quiz_id`, trong khi dự án thực tế thiết kế `QuizService` dùng `start()`, `getResult()` và gắn trực tiếp `course_id`. *Cách khắc phục:* Tự rà soát toàn bộ schema database và chữ ký hàm trong `QuizService.php`, sửa lại test case cho khớp thực tế để đạt 5/5 PASS.
2. **Thiếu kiểm tra thời gian quá hạn ở hàm `submit()` dẫn đến test case bị lỗi (FAIL):** Ở phiên bản đầu, logic kiểm tra quá giờ chỉ nằm ở hàm `start()` (khi mở lại bài cũ) mà chưa chặn trực tiếp tại thời điểm gọi `submit()`, khiến kịch bản người dùng cố tình nộp trễ qua API không ném ra `Exception`. *Cách khắc phục:* Tự bổ sung khối kiểm tra `started_at + quiz_minutes + 30s` ngay đầu hàm `QuizService::submit()` để vá lỗ hổng và giúp toàn bộ bộ test chạy xanh.