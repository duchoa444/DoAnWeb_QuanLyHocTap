# Nhật ký sử dụng AI - TV5 (Nguyễn Thành Nhân)

## Mục AI-01 (Ngày 1 - Thứ 2, Tuần 1)
- **Thời gian:** 09/11/2026
- **Công cụ AI:** Gemini
- **Công việc**: Tạo file .gitignore và mẫu quy tắc Git cho nhóm.
- **Prompt đã dùng**: "Viết file .gitignore chuẩn cho dự án Web và giải thích ý nghĩa các folder bị bỏ qua."
- **Gợi ý AI giữ lại**: Giữ các dòng loại bỏ `node_modules/`, `.env`, thư mục `dist/` và `build/`.
- **Gợi ý AI loại bỏ**: Bỏ các dòng cấu hình dành riêng cho Eclipse và NetBeans.

## Mục AI-02 (Ngày 2 - Thứ 3, Tuần 1)
- **Thời gian:** 10/11/2026
- **Công cụ AI:** Gemini
- **Công việc**: Tạo 5 kịch bản kiểm thử (test cases) cho chức năng làm bài trắc nghiệm (`HT-06`) vào file `docs/test-cases.md`.
- **Prompt đã dùng**: "Tạo 5 test case cho chức năng làm bài trắc nghiệm trực tuyến (HT-06) bao gồm các trường hợp: làm bài bình thường, nộp bài trễ giờ, hết lượt làm bài và bỏ trống câu hỏi."
- **Gợi ý AI giữ lại**: Giữ lại 5 tình huống kiểm thử cốt lõi (nộp bài hợp lệ, bỏ trống câu hỏi, đồng hồ đếm ngược hết giờ tự nộp, chặn gửi request nộp bài trễ và vô hiệu hóa nút làm bài khi hết lượt).
- **Điểm tự chỉnh sửa cho khớp tiêu chí**:
  - Định dạng lại bảng theo đúng 6 cột quy định của đồ án: `ID`, `Mã chức năng`, `Bước`, `Dữ liệu`, `Kết quả mong đợi`, `Kết quả thực tế`.
  - Chỉnh lại tên trường thời gian làm bài thành `quiz_minutes` cho khớp với thiết kế bảng `courses` trong ERD của nhóm.
  - Sửa cột `Kết quả thực tế` từ trạng thái giả định của AI sang `Pending` do hiện tại đang ở giai đoạn thiết kế, chưa chạy kiểm thử trên code thực tế.
  - Bổ sung điều kiện tài khoản học viên đã ghi danh vào khóa học ở cột `Dữ liệu` và `Bước` thực hiện.

  ---

## AI-03: Sinh bộ Test Case cho HT-03 và Checklist bảo mật chức năng Đăng ký

- **Thời gian:** 11/11/2026
- **Công cụ AI:** Gemini
- **Nhiệm vụ liên quan:** Thêm test case `HT-03` và lập checklist kiểm tra bảo mật khi review chức năng đăng ký (`feature/HT-03-dang-ky`).
- **Prompt đã sử dụng:**
  > "Viết bộ test case chi tiết cho chức năng HT-03 (Đăng ký tài khoản học viên) theo cấu trúc bảng của dự án Laravel 12 và liệt kê các lỗi bảo mật thường gặp ở chức năng đăng ký để làm checklist review code."
- **Kết quả AI hỗ trợ:**
  - Đề xuất 5 kịch bản kiểm thử (`TC-06` đến `TC-10`) bao phủ các trường hợp: đăng ký hợp lệ, trùng email, mật khẩu ngắn/không khớp, bỏ trống/sai định dạng trường bắt buộc và giả mạo tham số quyền (`role=admin`).
  - Liệt kê 4 điểm kiểm tra bảo mật trọng tâm: chống leo thang đặc quyền (Mass Assignment), bắt buộc băm mật khẩu (`Hash::make` / `bcrypt`), kiểm tra token `@csrf` trên form và quy tắc validate dữ liệu đầu vào tiếng Việt.
- **Kiểm tra & Chỉnh sửa của thành viên:**
  - Đã đối chiếu tên route `/register`, các trường dữ liệu (`name`, `email`, `password`) và cấu trúc phân quyền `student` cho khớp với thiết kế ERD v2 và khung dự án Laravel 12 của nhóm trước khi cập nhật vào `docs/test-cases.md`.

  ---

## AI-04: Tạo tài liệu cấu trúc thư mục dự án và rà soát repository cho Mốc 1

- **Thời gian:** 12/11/2026
- **Công cụ AI:** Gemini
- **Nhiệm vụ liên quan:** Tạo tài liệu `docs/cau-truc-thu-muc.md` trên nhánh `docs/cau-truc-thu-muc` và lập checklist tổng kiểm tra repository (`README.md`, `ai-logs/`, phân quyền 3 vai trò) trước hạn chốt Mốc 1.
- **Prompt đã sử dụng:**
  > "Soạn tài liệu mô tả cấu trúc thư mục chuẩn cho dự án Laravel 12 (docs/cau-truc-thu-muc.md) và lập danh sách các bước kiểm thử, rà soát repository (AI log, README, phân quyền 3 vai trò, merge PR) cho vai trò Git/QA Lead chuẩn bị báo cáo Mốc 1."
- **Kết quả AI hỗ trợ:**
  - Sinh sơ đồ cây thư mục chuẩn của dự án (`ai-logs/`, `app/Http/Middleware/`, `app/Services/`, `database/`, `docs/`, `resources/views/`, `routes/`) kèm quy ước lưu trữ tài liệu và tách lớp nghiệp vụ.
  - Đề xuất quy trình kiểm tra 4 hạng mục trước khi merge vào `develop`: đối chiếu số lượng mục AI log của từng thành viên, thống kê commit bằng `git shortlog -sn`, rà soát tài khoản demo 3 vai trò trong `README.md` và kiểm thử chặn truy cập trái phép giữa các route `/admin/*`, `/teacher/*`.
- **Kiểm tra & Chỉnh sửa của thành viên:**
  - Đã đối chiếu trực tiếp cây thư mục thực tế trên nhánh `develop` (các thư mục con trong `docs/` như `databases/`, `meetings/`, `wireframe/` và các file tài liệu hiện có) để chỉnh sửa mô tả trong `docs/cau-truc-thu-muc.md` khớp 100% với mã nguồn của nhóm.
  - Đã chạy kiểm tra thực tế `git shortlog -sn`, rà soát file trong `ai-logs/` của cả 5 thành viên và kiểm thử toàn bộ luồng cài đặt lại theo `README.md` sau khi merge PR.

  ## AI-05: Xây dựng chức năng HT-04 (Ghi danh & Khóa học của tôi) và rà soát bảo mật

- **Thời gian:** 18/11/2026
- **Công cụ AI:** Gemini
- **Nhiệm vụ liên quan:** Lập trình chức năng `HT-04` (ghi danh khóa học công khai tối đa 1 lần/khóa, trang "Khóa học của tôi") trên nhánh `feature/HT-04-ghi-danh` và rà soát lỗi bảo mật cho các PR Sprint 2 (`HT-09`, `HT-14`).
- **Prompt đã sử dụng:**
  > "Viết code Laravel 12 cho chức năng HT-04 gồm EnrollmentService (kiểm tra khóa học công khai, chặn ghi danh trùng), EnrollmentController, route web, giao diện Blade trang Khóa học của tôi (chưa hiển thị % tiến độ) và liệt kê checklist rà soát bảo mật cho các chức năng quản lý."
- **Kết quả AI hỗ trợ:**
  - Sinh lớp `App\Services\EnrollmentService` xử lý nghiệp vụ kiểm tra trạng thái `published`, kiểm tra trùng lặp `(user_id, course_id)` và truy vấn danh sách khóa học đã ghi danh.
  - Sinh `EnrollmentController`, khai báo route `POST /courses/{course}/enroll`, `GET /my-courses` và giao diện `resources/views/student/my-courses.blade.php`.
  - Đề xuất các điểm rà soát bảo mật trọng tâm: chống IDOR khi gọi trực tiếp route ghi danh/sửa khóa học, chống Mass Assignment, kiểm tra `@csrf`, XSS (`{{ }}`) và validate file upload.
- **Kiểm tra & Chỉnh sửa của thành viên:**
  - Đã đối chiếu tên cột (`status`, `user_id`, `course_id`, `enrolled_at`), tên quan hệ Eloquent (`enrollments`, `teacher`) và tên route (`courses.show`, `layouts.app`) cho khớp với migration và layout thực tế của nhóm.
  - Đã kiểm thử thực tế trên trình duyệt: ghi danh thành công khóa công khai, bị chặn khi bấm ghi danh lần 2 và hiển thị đúng danh sách tại trang "Khóa học của tôi".

## AI-06: Xây dựng chức năng HT-11 (CRUD câu hỏi trắc nghiệm và thời gian làm bài)

- **Thời gian:** 19/11/2026
- **Công cụ AI:** Gemini
- **Nhiệm vụ liên quan:** Lập trình chức năng `HT-11` (thêm, sửa, xóa câu hỏi trắc nghiệm 4 lựa chọn, cài đặt thời gian làm bài tính bằng phút và kiểm tra quyền sở hữu khóa học) trên nhánh `feature/HT-11-cau-hoi`.
- **Prompt đã sử dụng:**
  > "Viết code Laravel 12 cho chức năng HT-11 gồm QuestionController (CRUD câu hỏi 4 đáp án A/B/C/D với 1 đáp án đúng, cập nhật thời gian làm bài theo phút, kiểm tra chủ sở hữu khóa học ở server), khai báo route và tạo giao diện Blade quản lý câu hỏi cho giảng viên."
- **Kết quả AI hỗ trợ:**
  - Sinh lớp `App\Http\Controllers\Teacher\QuestionController` với hàm `authorizeCourseOwner()` chặn giảng viên chỉnh sửa câu hỏi của khóa học không thuộc quyền sở hữu (trả về lỗi 403).
  - Sinh logic validate tiếng Việt cho dữ liệu câu hỏi (`content`, `option_a`–`option_d`, `correct_option` thuộc `A,B,C,D`) và thời gian làm bài (`quiz_minutes`).
  - Tạo giao diện `resources/views/teacher/questions/index.blade.php` tích hợp form chỉnh thời gian làm bài, form thêm câu hỏi mới và danh sách câu hỏi kèm nút xóa.
- **Kiểm tra & Chỉnh sửa của thành viên:**
  - Đã đối chiếu tên cột khóa ngoại chủ sở hữu (`teacher_id` / `user_id`), cột thời gian làm bài (`quiz_minutes`) và tên các cột đáp án trong `database/migrations/` của nhóm để khớp với CSDL thực tế.
  - Đã kiểm thử thực tế trên trình duyệt: thêm/xóa câu hỏi, lưu số phút làm bài thành công và thử truy cập URL khóa học của giảng viên khác để xác nhận hệ thống chặn lỗi 403.

  ## AI-07: Hoàn thiện % tiến độ học tập (HT-04) và giải quyết xung đột merge HT-11

- **Thời gian:** 20/11/2026
- **Công cụ AI:** Gemini
- **Nhiệm vụ liên quan:** Giải quyết xung đột merge nhánh `feature/HT-11-cau-hoi` vào `develop`, cấu hình môi trường SQLite và lập trình tính năng hiển thị `% tiến độ` khóa học (`HT-04`) trên nhánh `feature/HT-04-tien-do`.
- **Prompt đã sử dụng:**
  > "Kiểm tra và gộp xung đột file routes/web.php khi merge nhánh feature/HT-11-cau-hoi vào develop, khắc phục lỗi cấu hình database SQLite, đồng thời viết logic tính phần trăm tiến độ khóa học (số bài đã hoàn thành / tổng số bài) trong Model Course và hiển thị thanh tiến độ tại trang Khóa học của tôi (HT-04)."
- **Kết quả AI hỗ trợ:**
  - Phát hiện lỗi trùng lặp cụm route và dư dấu đóng ngoặc trong file `routes/web.php`, đồng thời hướng dẫn cấu hình lại `.env` để Laravel kết nối đúng tới `database/database.sqlite`.
  - Sinh phương thức `getProgressStats(?int $userId)` trong `app/Models/Course.php` tính tỷ lệ hoàn thành theo công thức `số bài đã hoàn thành / tổng số bài` và cập nhật giao diện thanh tiến độ (`%`) tại `resources/views/courses/my-courses.blade.php`.
  - Sửa lỗi lệch tên route `student.my-courses` và đường dẫn view `courses.my-courses` trong `app/Http/Controllers/EnrollmentController.php`.
- **Kiểm tra & Chỉnh sửa của thành viên:**
  - Đã rà soát toàn bộ route trong `routes/web.php` khớp với các phân hệ học viên, giảng viên (`HT-11`), quản trị viên và hoàn tất merge PR #36 vào `develop`.
  - Bổ sung điều kiện kiểm tra `Schema::hasTable('lesson_progress')` và trường hợp khóa học có `0` bài học để tránh lỗi chia cho 0 hoặc lỗi thiếu bảng khi nhánh `HT-05` chưa merge.
  - Đã kiểm thử thực tế trên trình duyệt tại đường dẫn `/my-courses` với tài khoản học viên để xác nhận thanh tiến độ hiển thị chính xác trước khi tạo PR.

  ---

## AI-08: Bổ sung kịch bản kiểm thử (HT-04, HT-05, HT-06, HT-11) và kịch bản Smoke Test trước Mốc 2

- **Thời gian:** 20/11/2026
- **Công cụ AI:** Gemini
- **Nhiệm vụ liên quan:** Bổ sung bộ test case cho các chức năng `HT-04`, `HT-05`, `HT-06`, `HT-11` vào file `docs/test-cases.md`, rà soát merge PR trước 21:00 và lập kịch bản chạy Smoke Test kiểm chứng bảo mật (`HT-NFR1`, `HT-NFR2`, `HT-NFR3`) trên nhánh `develop`[cite: 2, 4].
- **Prompt đã sử dụng:**
  > "Viết bổ sung các kịch bản kiểm thử (test cases) theo đúng định dạng bảng 6 cột của dự án cho các chức năng HT-04, HT-05, HT-06, HT-11 và lập danh sách các bước chạy Smoke Test kèm kiểm chứng bảo mật HT-NFR1, HT-NFR2, HT-NFR3 trên nhánh develop."
- **Kết quả AI hỗ trợ:**
  - Sinh các kịch bản kiểm thử bao phủ luồng ghi danh và tính `% tiến độ` (`HT-04`), học bài và bảo vệ tệp PDF khi chưa ghi danh (`HT-05`, `HT-NFR2`), chống lộ đáp án qua Network / nộp quá giờ / giới hạn 3 lượt thi (`HT-06`, `HT-NFR1`) và kiểm tra quyền sở hữu khóa học khi sửa câu hỏi (`HT-11`, `HT-NFR3`).
  - Đề xuất trình tự chạy Smoke Test xuyên suốt luồng học viên (đăng nhập $\rightarrow$ ghi danh $\rightarrow$ học bài $\rightarrow$ kiểm tra `% tiến độ` $\rightarrow$ làm bài trắc nghiệm $\rightarrow$ xem lịch sử điểm) phục vụ buổi demo thử[cite: 2].
- **Kiểm tra & Chỉnh sửa của thành viên:**
  - Đã đối chiếu tên đường dẫn (`/my-courses`, `/courses/{id}/learn`), tên bảng (`enrollments`, `lesson_progress`) và tên cột (`quiz_minutes`, `teacher_id`) cho khớp 100% với mã nguồn thực tế trên nhánh `develop`.
  - Đã duyệt và merge các Pull Request hoàn thành vào `develop`, xóa nhánh phụ để không tồn đọng PR treo và chạy kiểm thử thực tế các quy tắc phân quyền, chống gian lận[cite: 4, 5].

---

## AI-09: Viết Unit Test chức năng Ghi danh (HT-04) và tổng hợp 35 Test Cases cho 12 chức năng

- **Thời gian:** 20/11/2026
- **Công cụ AI:** Gemini
- **Nhiệm vụ liên quan:** Viết unit test cho `EnrollmentService` trên nhánh `test/HT-04`, hoàn thiện bảng kịch bản kiểm thử đủ 12 chức năng bắt buộc trong `docs/test-cases.md`, cập nhật hướng dẫn chạy test vào `README.md` và gắn tag `v0.5` cho Mốc 2[cite: 8, 9].
- **Prompt đã sử dụng:**
  > "Viết unit test bằng PHPUnit trong Laravel 12 cho EnrollmentService (kiểm tra ghi danh hợp lệ và chặn ghi danh trùng lần 2), đồng thời gộp và định dạng lại file docs/test-cases.md thành một bảng tổng hợp đủ 12 chức năng bắt buộc (HT-01 đến HT-15) theo cấu trúc 6 cột."
- **Kết quả AI hỗ trợ:**
  - Sinh lớp `Tests\Unit\EnrollmentServiceTest` sử dụng `RefreshDatabase` với 2 phương thức kiểm thử: ghi danh thành công khóa học `published` và ném ngoại lệ `Exception` khi học viên ghi danh trùng lần 2.
  - Tổng hợp và chuẩn hóa 35 kịch bản kiểm thử (`TC-01` đến `TC-35`) bao phủ toàn bộ 12 chức năng bắt buộc và 3 yêu cầu bảo mật (`HT-NFR1`, `HT-NFR2`, `HT-NFR3`) vào một bảng thống nhất dễ theo dõi.
- **Kiểm tra & Chỉnh sửa của thành viên:**
  - Đã đối chiếu các trường dữ liệu khởi tạo (`category_id`, `teacher_id`, `quiz_minutes`, `status`) trong `EnrollmentServiceTest` cho khớp với migration thực tế và chạy `php artisan test` xác nhận toàn bộ test case đều đạt (Pass).
  - Đã bổ sung lệnh `php artisan test` vào `README.md`, merge nhánh `test/HT-04` vào `develop` và gắn tag `v0.5` trên GitHub[cite: 8, 9].