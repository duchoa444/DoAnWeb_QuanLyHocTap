# AI log – TV5 – Nguyễn Thành Nhân

Mỗi thành viên một tệp trong `ai-logs/`. Ghi một mục ngay sau mỗi lần dùng AI có ảnh hưởng đến sản phẩm, commit cùng thay đổi liên quan. Cả đồ án tối thiểu 6 mục có nội dung thực chất. Mã mục tăng dần: `AI-01`, `AI-02`, ...

| Mục | Thông tin |
| :--- | :--- |
| **Họ tên, MSSV** | Nguyễn Thành Nhân – `[Điền MSSV của bạn]` |
| **Nhóm, đề tài** | Nhóm 4 – Đề tài E: Quản lý học tập trực tuyến (`DoAnWeb_QuanLyHocTap`) |
| **Vai trò** | TV5 – Git & Kiểm thử (QA) |
| **Công cụ AI dùng** | Gemini |

---

## Bảng tổng hợp

| Mã | Ngày | Giai đoạn | Nội dung | Commit / PR |
| :--- | :---: | :--- | :--- | :--- |
| `AI-01` | 09/11 | Phân tích yêu cầu | Tạo file `.gitignore` và mẫu quy tắc Git cho nhóm | `chore: them .gitignore va quy tac git` |
| `AI-02` | 10/11 | Thiết kế | Tạo 5 kịch bản kiểm thử cho chức năng làm bài trắc nghiệm (`HT-06`) | `docs: them test case HT-06` |
| `AI-03` | 11/11 | Thiết kế | Sinh bộ Test Case cho `HT-03` và checklist bảo mật đăng ký | `docs: bo sung test case HT-03` |
| `AI-04` | 12/11 | Tài liệu | Tạo tài liệu cấu trúc thư mục dự án và rà soát repo Mốc 1 | `docs: tao tai lieu cau truc thu muc` |
| `AI-05` | 18/11 | Lập trình | Xây dựng chức năng `HT-04` (Ghi danh & Khóa học của tôi) | `feat: hoan thanh chuc nang ghi danh HT-04` |
| `AI-06` | 19/11 | Lập trình | Xây dựng chức năng `HT-11` (CRUD câu hỏi trắc nghiệm & thời gian thi) | `feat: hoan thanh quan ly cau hoi HT-11` |
| `AI-07` | 20/11 | Lập trình | Hoàn thiện `% tiến độ` học tập (`HT-04`) và giải quyết xung đột merge `HT-11` | `PR #36` |
| `AI-08` | 20/11 | Kiểm thử | Bổ sung kịch bản kiểm thử (`HT-04`–`HT-11`) và kịch bản Smoke Test Mốc 2 | `docs: bo sung test cases va smoke test` |
| `AI-09` | 20/11 | Kiểm thử | Viết Unit Test `EnrollmentService` và tổng hợp 35 Test Cases cho 12 chức năng | `tag v0.5` |

---

### [AI-01] Tạo file `.gitignore` và mẫu quy tắc Git cho nhóm
- **Ngày / Người dùng:** 2026-11-09 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Phân tích yêu cầu
- **Task / Commit / PR:** `[GIT] Thiết lập repo` / `chore: them .gitignore va quy tac git`
- **Mục đích:** Khởi tạo tệp `.gitignore` chuẩn cho dự án Web và thống nhất quy tắc đặt tên nhánh, commit cho 5 thành viên.
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Viết file .gitignore chuẩn cho dự án Web và giải thích ý nghĩa các folder bị bỏ qua."*
- **Kết quả AI trả về (tóm tắt):** Sinh mẫu `.gitignore` loại bỏ `node_modules/`, `.env`, `dist/`, `build/`, cùng các cấu hình IDE phổ biến (VS Code, Eclipse, NetBeans).
- **Đánh giá:** Đúng một phần – đủ các thư mục cơ bản cho dự án Web nhưng dư thừa cấu hình của các IDE không dùng (Eclipse, NetBeans) và thiếu thư mục `/vendor` đặc thù của Laravel.
- **Đã chỉnh sửa gì:** Giữ lại các dòng loại bỏ `node_modules/`, `.env`, `dist/`, `build/`; xóa các dòng cấu hình Eclipse, NetBeans và bổ sung thêm `/vendor`, `database/*.sqlite` cho khớp với dự án Laravel.
- **Cách kiểm chứng:** Kiểm tra lệnh `git status` trên máy local đảm bảo tệp `.env` và thư mục `vendor/` không bị đưa vào vùng staging.
- **Tỷ lệ mã AI còn giữ lại (ước lượng):** 60%
- **Điều học được:** Khi nhờ AI tạo cấu hình môi trường, cần nêu rõ framework cụ thể (Laravel 12) để tránh sinh dư thừa cấu hình của công nghệ khác.

### [AI-02] Tạo 5 kịch bản kiểm thử cho chức năng làm bài trắc nghiệm (`HT-06`)
- **Ngày / Người dùng:** 2026-11-10 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Thiết kế
- **Task / Commit / PR:** `[TEST] Test case HT-06` / `docs: them test case HT-06`
- **Mục đích:** Xây dựng các kịch bản kiểm thử biên cho chức năng làm bài trắc nghiệm trực tuyến (`HT-06`) vào tệp `docs/test-cases.md`.
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Tạo 5 test case cho chức năng làm bài trắc nghiệm trực tuyến (HT-06) bao gồm các trường hợp: làm bài bình thường, nộp bài trễ giờ, hết lượt làm bài và bỏ trống câu hỏi."*
- **Kết quả AI trả về (tóm tắt):** Đề xuất 5 tình huống kiểm thử cốt lõi: nộp bài hợp lệ, bỏ trống câu hỏi, đồng hồ đếm ngược hết giờ tự động nộp, chặn gửi request nộp bài trễ và vô hiệu hóa nút làm bài khi hết 3 lượt.
- **Đánh giá:** Đúng một phần – bao phủ tốt các luồng nghiệp vụ biên nhưng sai định dạng cột bảng và tên trường CSDL so với tài liệu thiết kế của nhóm.
- **Đã chỉnh sửa gì:** Định dạng lại bảng theo đúng 6 cột quy định (`ID`, `Mã chức năng`, `Bước`, `Dữ liệu`, `Kết quả mong đợi`, `Kết quả thực tế`); sửa tên trường thời gian thành `quiz_minutes` cho khớp ERD; đổi trạng thái `Kết quả thực tế` thành `Pending` và bổ sung điều kiện tài khoản đã ghi danh.
- **Cách kiểm chứng:** Đối chiếu lại với sơ đồ ERD và tài liệu đặc tả chức năng `HT-06` của nhóm.
- **Tỷ lệ nội dung AI còn giữ lại (ước lượng):** 70%
- **Điều học được:** Cần cung cấp sẵn mẫu tiêu đề cột và tên trường CSDL trong prompt để kết quả xuất ra khớp ngay với chuẩn tài liệu của nhóm.

### [AI-03] Sinh bộ Test Case cho `HT-03` và Checklist bảo mật chức năng Đăng ký
- **Ngày / Người dùng:** 2026-11-11 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Thiết kế
- **Task / Commit / PR:** `[TEST] Test case HT-03` / `docs: bo sung test case HT-03`
- **Mục đích:** Bổ sung kịch bản kiểm thử cho chức năng đăng ký tài khoản (`HT-03`) và lập checklist bảo mật phục vụ review code.
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Viết bộ test case chi tiết cho chức năng HT-03 (Đăng ký tài khoản học viên) theo cấu trúc bảng của dự án Laravel 12 và liệt kê các lỗi bảo mật thường gặp ở chức năng đăng ký để làm checklist review code."*
- **Kết quả AI trả về (tóm tắt):** Đề xuất 5 test case (`TC-06` đến `TC-10`: đăng ký hợp lệ, trùng email, mật khẩu ngắn/không khớp, bỏ trống trường bắt buộc, giả mạo tham số `role=admin`) và 4 điểm kiểm tra bảo mật (Mass Assignment, băm mật khẩu `Hash::make`, `@csrf`, validate tiếng Việt).
- **Đánh giá:** Đúng – các tình huống kiểm thử và lỗ hổng bảo mật sát với thực tế phát triển ứng dụng Laravel 12.
- **Đã chỉnh sửa gì:** Điều chỉnh tên route `/register`, các trường dữ liệu (`name`, `email`, `password`) và giá trị phân quyền mặc định `student` cho khớp với ERD v2 của nhóm trước khi lưu vào `docs/test-cases.md`.
- **Cách kiểm chứng:** Đối chiếu với thiết kế bảng `users` trong ERD v2 và khung route xác thực của nhóm.
- **Tỷ lệ nội dung AI còn giữ lại (ước lượng):** 85%
- **Điều học được:** Kết hợp yêu cầu viết test case cùng checklist bảo mật giúp QA chủ động phát hiện sớm lỗi leo thang đặc quyền ngay từ khâu review code.

### [AI-04] Tạo tài liệu cấu trúc thư mục dự án và rà soát repository cho Mốc 1
- **Ngày / Người dùng:** 2026-11-12 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Tài liệu
- **Task / Commit / PR:** `[DOC] Cấu trúc thư mục` / `docs: tao tai lieu cau truc thu muc` (`docs/cau-truc-thu-muc.md`)
- **Mục đích:** Chuẩn hóa sơ đồ cây thư mục dự án và lập danh sách rà soát repository (`ai-logs/`, `README.md`, phân quyền 3 vai trò) trước khi chốt Mốc 1.
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Soạn tài liệu mô tả cấu trúc thư mục chuẩn cho dự án Laravel 12 (docs/cau-truc-thu-muc.md) và lập danh sách các bước kiểm thử, rà soát repository (AI log, README, phân quyền 3 vai trò, merge PR) cho vai trò Git/QA Lead chuẩn bị báo cáo Mốc 1."*
- **Kết quả AI trả về (tóm tắt):** Sinh sơ đồ cây thư mục chuẩn (`ai-logs/`, `app/Http/Middleware/`, `app/Services/`, `database/`, `docs/`, `resources/views/`, `routes/`) và 4 bước kiểm tra repo trước khi merge vào `develop`.
- **Đánh giá:** Thiếu một phần – sơ đồ AI gợi ý chưa phản ánh hết các thư mục con đặc thù mà nhóm đã tạo trong `docs/`.
- **Đã chỉnh sửa gì:** Đối chiếu trực tiếp cây thư mục thực tế trên nhánh `develop`, bổ sung mô tả cho các thư mục con `docs/databases/`, `docs/meetings/`, `docs/wireframe/` và các tệp tài liệu đặc tả hiện có.
- **Cách kiểm chứng:** Chạy `git shortlog -sn`, kiểm tra thủ công toàn bộ file trong `ai-logs/` của 5 thành viên và cài đặt thử dự án theo `README.md`.
- **Tỷ lệ nội dung AI còn giữ lại (ước lượng):** 75%
- **Điều học được:** Tài liệu mô tả cấu trúc dự án luôn phải đối chiếu trực tiếp với cây thư mục thật trên nhánh `develop` thay vì dùng hoàn toàn cấu trúc mặc định của AI.

### [AI-05] Xây dựng chức năng `HT-04` (Ghi danh & Khóa học của tôi) và rà soát bảo mật
- **Ngày / Người dùng:** 2026-11-18 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Lập trình
- **Task / Commit / PR:** `[HT-04] Ghi danh khóa học` / `feat: hoan thanh chuc nang ghi danh HT-04` (`feature/HT-04-ghi-danh`)
- **Mục đích:** Cài đặt nghiệp vụ ghi danh khóa học công khai (tối đa 1 lần/khóa), hiển thị danh sách tại trang "Khóa học của tôi" và rà soát bảo mật cho các PR Sprint 2 (`HT-09`, `HT-14`).
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Viết code Laravel 12 cho chức năng HT-04 gồm EnrollmentService (kiểm tra khóa học công khai, chặn ghi danh trùng), EnrollmentController, route web, giao diện Blade trang Khóa học của tôi (chưa hiển thị % tiến độ) và liệt kê checklist rà soát bảo mật cho các chức năng quản lý."*
- **Kết quả AI trả về (tóm tắt):** Sinh lớp `App\Services\EnrollmentService`, `EnrollmentController`, route `POST /courses/{course}/enroll`, `GET /my-courses`, giao diện `resources/views/student/my-courses.blade.php` và checklist chống IDOR, Mass Assignment, XSS.
- **Đánh giá:** Đúng một phần – cấu trúc tách lớp Service rõ ràng nhưng tên một số cột và layout chưa khớp với migration thực tế của nhóm.
- **Đã chỉnh sửa gì:** Chỉnh lại tên cột (`status`, `user_id`, `course_id`, `enrolled_at`), tên quan hệ Eloquent (`enrollments`, `teacher`) và tên layout (`layouts.app`) cho đồng bộ với code của các thành viên khác.
- **Cách kiểm chứng:** Chạy thử trên trình duyệt với tài khoản học viên: ghi danh thành công khóa công khai, bị chặn thông báo lỗi khi bấm ghi danh lần 2 và hiển thị đúng danh sách tại `/my-courses`.
- **Tỷ lệ mã AI còn giữ lại (ước lượng):** 75%
- **Điều học được:** Tách logic nghiệp vụ vào lớp `EnrollmentService` giúp controller gọn nhẹ và dễ viết Unit Test về sau.

### [AI-06] Xây dựng chức năng `HT-11` (CRUD câu hỏi trắc nghiệm và thời gian làm bài)
- **Ngày / Người dùng:** 2026-11-19 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Lập trình
- **Task / Commit / PR:** `[HT-11] Quản lý câu hỏi` / `feat: hoan thanh quan ly cau hoi HT-11` (`feature/HT-11-cau-hoi`)
- **Mục đích:** Lập trình chức năng cho giảng viên thêm, sửa, xóa câu hỏi trắc nghiệm 4 đáp án (`A/B/C/D`), cấu hình thời gian làm bài (`quiz_minutes`) và kiểm tra quyền sở hữu khóa học ở server.
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Viết code Laravel 12 cho chức năng HT-11 gồm QuestionController (CRUD câu hỏi 4 đáp án A/B/C/D với 1 đáp án đúng, cập nhật thời gian làm bài theo phút, kiểm tra chủ sở hữu khóa học ở server), khai báo route và tạo giao diện Blade quản lý câu hỏi cho giảng viên."*
- **Kết quả AI trả về (tóm tắt):** Sinh `App\Http\Controllers\Teacher\QuestionController` kèm hàm `authorizeCourseOwner()` chặn lỗi 403, bộ quy tắc validate tiếng Việt (`content`, `option_a`–`option_d`, `correct_option`, `quiz_minutes`) và view `resources/views/teacher/questions/index.blade.php`.
- **Đánh giá:** Đúng – logic phân quyền chủ sở hữu và validate dữ liệu đầu vào chặt chẽ.
- **Đã chỉnh sửa gì:** Đối chiếu và chỉnh lại tên cột khóa ngoại (`teacher_id`), cột `quiz_minutes` và các cột đáp án trong `database/migrations/` của nhóm cho khớp 100% với CSDL.
- **Cách kiểm chứng:** Kiểm thử trên trình duyệt với 2 tài khoản giảng viên khác nhau: giảng viên chủ sở hữu thêm/xóa câu hỏi và lưu số phút làm bài thành công; giảng viên khác truy cập chéo URL bị chặn lỗi 403.
- **Tỷ lệ mã AI còn giữ lại (ước lượng):** 80%
- **Điều học được:** Luôn phải kiểm tra quyền sở hữu tài nguyên (`authorizeCourseOwner`) ở phía server cho mọi thao tác thêm/sửa/xóa của giảng viên, không chỉ ẩn nút trên giao diện.

### [AI-07] Hoàn thiện `% tiến độ` học tập (`HT-04`) và giải quyết xung đột merge `HT-11`
- **Ngày / Người dùng:** 2026-11-20 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Lập trình
- **Task / Commit / PR:** `[HT-04] Tiến độ học tập` / `PR #36` (`feature/HT-04-tien-do`)
- **Mục đích:** Xử lý xung đột khi merge `feature/HT-11-cau-hoi` vào `develop`, sửa lỗi cấu hình SQLite và bổ sung hiển thị `% tiến độ` hoàn thành khóa học tại trang "Khóa học của tôi".
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Kiểm tra và gộp xung đột file routes/web.php khi merge nhánh feature/HT-11-cau-hoi vào develop, khắc phục lỗi cấu hình database SQLite, đồng thời viết logic tính phần trăm tiến độ khóa học (số bài đã hoàn thành / tổng số bài) trong Model Course và hiển thị thanh tiến độ tại trang Khóa học của tôi (HT-04)."*
- **Kết quả AI trả về (tóm tắt):** Chỉ ra đoạn route trùng lặp và dư dấu ngoặc trong `routes/web.php`, hướng dẫn chỉnh `.env` sang SQLite, sinh hàm `getProgressStats(?int $userId)` trong `app/Models/Course.php` và sửa lệch đường dẫn view trong `EnrollmentController.php`.
- **Đánh giá:** Đúng một phần – công thức tính phần trăm đúng nhưng code hàm `getProgressStats` truy vấn thẳng bảng `lesson_progress` mà chưa kiểm tra bảng tồn tại hay trường hợp khóa học có `0` bài học.
- **Đã chỉnh sửa gì:** Bổ sung kiểm tra `Schema::hasTable('lesson_progress')` và điều kiện `total > 0` để tránh lỗi thiếu bảng khi chưa merge nhánh `HT-05` và tránh lỗi chia cho 0 (`DivisionByZeroError`).
- **Cách kiểm chứng:** Merge `PR #36` vào `develop`, truy cập `/my-courses` bằng tài khoản học viên và kiểm tra thanh tiến độ hiển thị chính xác với cả khóa có bài học lẫn khóa chưa có bài học.
- **Tỷ lệ mã AI còn giữ lại (ước lượng):** 70%
- **Điều học được:** Khi viết các hàm tính toán thống kê phụ thuộc vào bảng do thành viên khác phát triển, cần bọc điều kiện kiểm tra sự tồn tại của bảng và dữ liệu rỗng để tránh làm sập trang chung.

### [AI-08] Bổ sung kịch bản kiểm thử (`HT-04`, `HT-05`, `HT-06`, `HT-11`) và kịch bản Smoke Test trước Mốc 2
- **Ngày / Người dùng:** 2026-11-20 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Kiểm thử
- **Task / Commit / PR:** `[TEST] Smoke Test Mốc 2` / `docs: bo sung test cases va smoke test`
- **Mục đích:** Hoàn thiện kịch bản kiểm thử cho các chức năng cốt lõi Sprint 2 và lập quy trình chạy Smoke Test kiểm chứng bảo mật (`HT-NFR1`, `HT-NFR2`, `HT-NFR3`) trên `develop`.
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Viết bổ sung các kịch bản kiểm thử (test cases) theo đúng định dạng bảng 6 cột của dự án cho các chức năng HT-04, HT-05, HT-06, HT-11 và lập danh sách các bước chạy Smoke Test kèm kiểm chứng bảo mật HT-NFR1, HT-NFR2, HT-NFR3 trên nhánh develop."*
- **Kết quả AI trả về (tóm tắt):** Sinh các test case cho luồng ghi danh, học bài, bảo vệ tệp PDF khi chưa ghi danh, chống lộ đáp án qua Network, giới hạn 3 lượt thi, kiểm tra chủ sở hữu câu hỏi và trình tự chạy Smoke Test luồng học viên.
- **Đánh giá:** Đúng – bao quát toàn bộ các điểm kiểm tra bảo mật phi chức năng theo yêu cầu đồ án.
- **Đã chỉnh sửa gì:** Chỉnh lại các đường dẫn URL (`/my-courses`, `/courses/{id}/learn`), tên bảng (`enrollments`, `lesson_progress`) và tên cột (`quiz_minutes`, `teacher_id`) cho khớp 100% với mã nguồn thực tế trên `develop`.
- **Cách kiểm chứng:** Thực thi trực tiếp toàn bộ kịch bản Smoke Test và kiểm thử bảo mật (mở tab Network/View Source, truy cập chéo URL) trên nhánh `develop` sau khi merge hết PR.
- **Tỷ lệ nội dung AI còn giữ lại (ước lượng):** 80%
- **Điều học được:** Chạy Smoke Test theo luồng người dùng đầu-cuối (end-to-end) sau khi merge giúp phát hiện nhanh lỗi lệch tên route giữa các phân hệ.

### [AI-09] Viết Unit Test chức năng Ghi danh (`HT-04`) và tổng hợp 35 Test Cases cho 12 chức năng
- **Ngày / Người dùng:** 2026-11-20 / Nguyễn Thành Nhân
- **Công cụ:** Gemini
- **Giai đoạn:** Kiểm thử
- **Task / Commit / PR:** `[TEST] Unit Test HT-04` / `tag v0.5` (`test/HT-04`)
- **Mục đích:** Viết kiểm thử tự động PHPUnit cho `EnrollmentService`, tổng hợp bảng test case đủ 12 chức năng bắt buộc trong `docs/test-cases.md` và gắn tag `v0.5` cho Mốc 2.
- **Prompt chính (nguyên văn hoặc link chia sẻ hội thoại):** *"Viết unit test bằng PHPUnit trong Laravel 12 cho EnrollmentService (kiểm tra ghi danh hợp lệ và chặn ghi danh trùng lần 2), đồng thời gộp và định dạng lại file docs/test-cases.md thành một bảng tổng hợp đủ 12 chức năng bắt buộc (HT-01 đến HT-15) theo cấu trúc 6 cột."*
- **Kết quả AI trả về (tóm tắt):** Sinh lớp `Tests\Unit\EnrollmentServiceTest` sử dụng `RefreshDatabase` (2 test methods) và gộp 35 test cases (`TC-01` đến `TC-35`) bao phủ 12 chức năng bắt buộc cùng 3 yêu cầu phi chức năng.
- **Đánh giá:** Đúng một phần – logic kiểm thử PHPUnit chính xác nhưng thiếu một số trường khóa ngoại bắt buộc (`category_id`, `teacher_id`) khi tạo dữ liệu mẫu `Course` trong test.
- **Đã chỉnh sửa gì:** Bổ sung đầy đủ các trường `category_id`, `teacher_id`, `quiz_minutes`, `status` khi khởi tạo bản ghi `Course` trong `EnrollmentServiceTest` và cập nhật hướng dẫn chạy `php artisan test` vào `README.md`.
- **Cách kiểm chứng:** Chạy lệnh `php artisan test` trên Terminal xác nhận tất cả các ca kiểm thử đều Pass, merge `test/HT-04` vào `develop` và gắn `tag v0.5` trên GitHub.
- **Tỷ lệ mã AI còn giữ lại (ước lượng):** 80%
- **Điều học được:** Khi viết Unit Test có tương tác CSDL (`RefreshDatabase`), cần khởi tạo đầy đủ các cột có ràng buộc `NOT NULL` hoặc khóa ngoại theo đúng file migration.

---

## Phản tư sử dụng AI – TV5 (Nguyễn Thành Nhân)

### 1. Hai trường hợp AI hỗ trợ hiệu quả (Làm tốt)
- **Trường hợp 1 – Sinh khung Unit Test và bộ kịch bản kiểm thử biên/bảo mật (`HT-01` đến `HT-15`, `HT-NFR1` đến `HT-NFR4`):** AI giúp tổng hợp nhanh các tình huống kiểm thử dễ bị bỏ sót như kiểm tra lỗi chia cho 0 khi khóa học chưa có bài học, chặn ghi danh trùng lần 2 trong `EnrollmentServiceTest`, kiểm tra chống lộ đáp án dưới mã nguồn trình duyệt và chặn truy cập chéo khóa học giữa các giảng viên, giúp rút ngắn đáng kể thời gian soạn tài liệu QA.
- **Trường hợp 2 – Rà soát xung đột merge và chuẩn hóa kiểm tra quyền sở hữu (`HT-NFR3`):** Khi gộp các nhánh `feature/HT-11-cau-hoi` và `feature/HT-04-tien-do` vào `develop`, AI hỗ trợ phát hiện nhanh đoạn route trùng lặp trong `routes/web.php` và đề xuất hàm `authorizeCourseOwner()` kiểm tra chủ sở hữu ở phía server trước khi cho phép thao tác với câu hỏi.

### 2. Hai trường hợp AI đưa ra kết quả chưa chính xác (Cần tự kiểm tra và chỉnh sửa)
- **Trường hợp 1 – Lệch mã chức năng, tên cột CSDL và đường dẫn view thực tế:** Ở các lần gợi ý đầu tiên, AI tự suy đoán cấu trúc đường dẫn view (`courses.my-courses` thay vì `student.my-courses`) và thiếu các cột khóa ngoại bắt buộc (`category_id`, `teacher_id`) khi tạo dữ liệu giả lập trong PHPUnit. Tôi phải đối chiếu trực tiếp với tài liệu Đặc tả đề tài E, cây thư mục thực tế và các tệp trong `database/migrations/` để sửa lại cho khớp 100%.
- **Trường hợp 2 – Viết truy vấn tính `% tiến độ` thiếu điều kiện phòng vệ khi chưa merge đủ nhánh:** Trong hàm `Course::getProgressStats`, code AI viết ban đầu truy vấn thẳng bảng `lesson_progress` và chia trực tiếp cho tổng số bài học, gây lỗi khi chạy trên nhánh chưa merge `HT-05` hoặc khi khóa học có `0` bài học. Tôi đã tự bổ sung điều kiện `Schema::hasTable('lesson_progress')` và xử lý trường hợp `total = 0` để tránh lỗi `DivisionByZeroError`.