NHẬT KÝ SỬ DỤNG AI - NGUYỄN HOÀNG ANH VŨ (TV1 - LE)
## AI-01: Bổ sung luồng ngoại lệ đặc tả Use Case và Use Case Diagram
- **Thời gian:** 09:30 – 10:45 (Thứ 3, Ngày 2 – Tuần 1)
- **Công cụ AI:** Gemini
- **Prompt:** 
    "Gợi ý Use Case Diagram 4 tác nhân (Khách vãng lai, Học viên, Giảng viên, Quản trị) và rà soát đặc tả 3   Use Case: Làm bài trắc nghiệm, Quản lý bài học, Ghi danh khóa học để tìm các luồng ngoại lệ/luồng phụ còn thiếu (như hết giờ, hết lượt làm bài, trùng lặp ghi danh)"
- **Kết quả AI phản hồi & Đánh giá:**
    AI đã liệt kê đầy đủ luồng chính và bổ sung chính xác các luồng ngoại lệ thực tế (hết giờ đếm ngược tự động nộp bài, hết số lượt làm bài cho phép quiz_attempts, và xử lý lỗi ràng buộc duy nhất UNIQUE(user_id, course_id) khi ghi danh).

- **Bài học rút ra**: Tiết kiệm hơn 70% thời gian soạn thảo tài liệu đặc tả.
## AI-02: Thiết kế Sequence Diagram "Làm bài trắc nghiệm" & Lập Biên bản họp nhóm 02
- **Thời gian:** 17:30 – 20:15 (Thứ 3, Ngày 2 – Tuần 1)
- **Công cụ AI:** Gemini
- **Prompt:**   
    "Gợi ý mã PlantUML cho Sequence Diagram chức năng 'Làm bài trắc nghiệm' gồm 2 Phase: start (tạo lượt quiz_attempts, ghi start_time, trả câu hỏi KHÔNG kèm đáp án) và submit (kiểm tra thời gian, chấm điểm, lưu DB). Đồng thời soạn mẫu Biên bản họp nhóm 02 lúc 20:00 chốt đại diện trình bày Mốc 1 "
- **Kết quả AI phản hồi & Đánh giá:**
    AI đã sinh mã PlantUML chính xác theo kiến trúc 3 lớp (Controller -> Service -> DB), phân tách đúng 2 phase start và submit, xử lý ẩn đáp án đúng để chống hack/inspect F12 . Mẫu Biên bản họp 02 được soạn ngắn gọn, đúng các mục tiêu rà soát tiến độ và phân công báo cáo Mốc 1
- **Bài học rút ra**: 
    Khi thiết kế Sequence Diagram cho chức năng thi/làm bài trắc nghiệm, luôn phải bảo mật đáp án bằng cách không trả về correct_option cho phía Client ở bước khởi tạo

    Toàn bộ logic tính toán thời gian làm bài thực tế và chấm điểm phải được thực hiện và kiểm tra ở phía Server/Backend khi người dùng bấm submit để chống gian lận
## AI-03: Thiết kế CSDL và xây dựng chức năng Quản lý người dùng (HT-14)
- **Ngày thực hiện:** 15/11/2026
- **Thẻ Trello liên quan:** `[HT-14] Quản lý người dùng`
- **Công cụ AI sử dụng:** Gemini
- **Mục đích & Prompt tóm tắt:** Tra cứu tiêu chí chấp nhận của chức năng `HT-14` theo đặc tả đề tài Quản lý học tập; nhờ gợi ý cấu trúc migration bổ sung trạng thái khóa tài khoản và phân tách luồng xử lý 3 lớp `UserController -> UserService -> UserRepository`.
- **Kết quả AI gợi ý:** Đề xuất tạo migration `add_is_active_to_users_table` để quản lý trạng thái khóa/mở khóa tài khoản; gợi ý khung xử lý băm mật khẩu `bcrypt` khi tạo tài khoản giảng viên (`teacher`) và phân trang phía server.
- **Kiểm chứng & Chỉnh sửa của sinh viên:**
  - Kiểm tra thứ tự các tệp migration, chạy `php artisan migrate` thành công trên CSDL `doan_quanlyhoctap` và xác nhận cột `is_active` trong bảng `users`.
  - Tự viết giao diện Blade quản lý người dùng, bổ sung thông báo lỗi tiếng Việt khi nhập sai dữ liệu và kiểm thử chặn đăng nhập đối với tài khoản bị khóa.
  ## AI-04: Cập nhật bổ sung luồng ngoại lệ đặc tả Use Case và Use Case Diagram
- **Thời gian:** 16/11/2026
- **Công cụ AI:** Gemini
- **Prompt:**
  1. *Dựa vào đặt tả 16 Chức năng gợi ý usecase diagram*
  2. *"Đặc tả 3 use case: Làm bài trắc nghiệm, Quản lý bài học, Ghi danh khóa học (luồng phụ: hết giờ, hết lượt làm bài). file .md"* (Yêu cầu viết đặc tả chi tiết kèm luồng phụ và luồng ngoại lệ dưới dạng tệp Markdown).
  3. *"Sequence Làm bài trắc nghiệm; họp, biên bản 02"* (Yêu cầu dựng biểu đồ tuần tự cho chức năng `HT-06` ).
- **Kết quả AI phản hồi & Đánh giá:**
  - **Kết quả phản hồi:**
    - Sơ đồ phác họa UseCase từ 16 chức năng - các extend và include.Cung cấp quy trình 3 bước cấu hình trên Draw.io (chuyển giao diện sang *Light*, khóa màu nền `#FFFFFF`, xuất PNG ở mức Zoom `300%–400%` và bỏ chọn *Transparent Background*) kèm hướng dẫn chống nén ảnh khi chèn vào Word/LaTeX.
    - Gợi ý đặc tả đầy đủ 3 Use Case (`HT-04`, `HT-06`, `HT-10`), bao gồm luồng sự kiện chính, các luồng phụ (*Hết giờ tự động nộp bài*, *Hết lượt làm bài*, *Khôi phục phiên thi dở*, *Cập nhật/Xóa mềm bài học*) và bảng luồng ngoại lệ chi tiết.
    - Luồng Sequence làm bài trắc nghiệm
  - **Đánh giá:** Nội dung đặc tả và sơ đồ bám sát danh sách chức năng `HT-01`–`HT-16` của đề tài, logic xử lý ngoại lệ chặt chẽ và trình bày rõ ràng. Tuy nhiên, ở câu hỏi đầu tiên AI hiểu nhầm sang việc tự dựng ảnh thay vì hướng dẫn chỉnh trên Draw.io nên cần phản hồi làm rõ; phần thông tin hành chính (ngày họp, họ tên, MSSV) trong biên bản họp vẫn cần nhóm tự kiểm tra và điền thủ công.
- **Bài học rút ra:**
  - Cần chỉ rõ các **luồng phụ, luồng ngoại lệ và ràng buộc nghiệp vụ** (hết giờ, hết lượt, giới hạn dung lượng tệp PDF, xóa mềm dữ liệu) ngay trong prompt để AI sinh đặc tả và biểu đồ tuần tự sát với thực tế triển khai.
  - Khi cần hướng dẫn thao tác trên phần mềm (như Draw.io), nên nêu rõ tên công cụ và mục tiêu thao tác ngay từ đầu để tránh AI tự sinh sản phẩm thay thế.
  - Luôn rà soát, đối chiếu lại tên hàm, thực thể dữ liệu và thông tin thành viên do AI tạo ra với tài liệu chuẩn của nhóm trước khi chốt đưa vào báo cáo đồ án.
  ## AI-05: Tích hợp chức năng Duyệt khóa học (HT-15) vào trang Quản lý người dùng
- **Thời gian:** 08:30 - 09:35 (Thứ 3, Ngày 17/11)
- **Công cụ AI:** Gemini
- **Prompt:**
  > "Gộp giao diện Duyệt khóa học (HT-15) vào chung trang admin/users/index.blade.php đồng bộ CSS với phần Quản lý tài khoản (HT-14) theo kiến trúc 3 lớp (Controller - Service - Repository), tách phân trang courses_page, thêm modal xem chi tiết khóa học và xử lý các lỗi xung đột route, truy vấn trạng thái dữ liệu thực tế."
- **Kết quả AI phản hồi & Đánh giá:**
  > AI đã cung cấp đầy đủ mã nguồn cho mô hình 3 lớp (UserManagementController, Admin\CourseController, CourseService, CourseRepository) và giao diện Blade đồng nhất với khuôn bảng có sẵn, kèm hộp thoại xem nhanh chi tiết khóa học sử dụng thuộc tính data-*. Ngoài ra, AI hỗ trợ chẩn đoán và sửa chính xác các lỗi phát sinh trong quá trình tích hợp như: đồng bộ trạng thái 'public'/'pending' trong CSDL với bộ lọc 'draft'/'published', chuẩn hóa nhóm route admin.* trong routes/web.php, và khắc phục lỗi biên dịch Blade (@forelse, ký tự toán tử).

- **Bài học rút ra**: Cần đối chiếu trực tiếp giá trị dữ liệu mẫu trong CSDL với điều kiện truy vấn trên code, đồng thời rà soát kỹ trùng lặp hàm/route và cú pháp Blade sau khi ghép mã nguồn.
## AI-06: Xử lý lỗi tích hợp xác thực (HT-03), chuẩn hóa định tuyến và đồng bộ giao diện
- **Thời gian:** 20:00 - 21:00 (Thứ 4, Ngày 18/11)
- **Công cụ AI:** Gemini
- **Prompt:**
  > "Kiểm tra và khắc phục các lỗi phát sinh khi ghép code toàn nhóm: trùng lặp route trong routes/web.php, lỗi 500 khi đăng nhập do lệch tên hàm giữa AuthController và AuthService, lỗi 419 Page Expired khi đăng xuất, cập nhật liên kết menu Admin trong header.blade.php và kiểm tra cảnh báo kiểu dữ liệu trong courses/index.blade.php."
- **Kết quả AI phản hồi & Đánh giá:**
  > AI đã chỉ ra chính xác dòng route `/courses` bị khai báo trùng ở cuối `routes/web.php`, đồng thời đối chiếu chữ ký hàm giữa `AuthController` và `AuthService` của thành viên phụ trách HT-03 (`register`, `login`, `Auth::logout()`) để sửa triệt để lỗi 500 mà không gây xung đột mã nguồn chung. Ngoài ra, AI giải thích rõ nguyên nhân lỗi `419 Page Expired` do token phiên cũ sau khi `regenerate()` session, cập nhật lại liên kết `/admin/users#courses-section` trên `header.blade.php` và xác nhận tính đồng bộ của `CourseController` với trạng thái khóa học `published`/`public`.

- **Bài học rút ra**: Khi tích hợp mã nguồn từ nhiều thành viên, cần thống nhất chặt chẽ tên phương thức ở tầng Service, rà soát các route khai báo trùng lặp và luôn làm mới phiên/cache (`route:clear`, tải lại trang nhận mã `@csrf` mới) trước khi kiểm thử luồng xác thực.

---

## AI-07: Tổng hợp tiến độ 16 chức năng và xây dựng Kịch bản Demo (Biên bản 04)
- **Thời gian:** 21:00 - 21:30 (Thứ 4, Ngày 18/11)
- **Công cụ AI:** Gemini
- **Prompt:**
  > "Tổng hợp Biên bản họp nhóm lần 04 đánh giá đầy đủ tình trạng hoàn thành của cả 16 chức năng (HT-01 đến HT-16), phân công nhiệm vụ hoàn thiện cho từng thành viên trước buổi bảo vệ và xây dựng kịch bản Demo chi tiết theo luồng nghiệp vụ thực tế."
- **Kết quả AI phản hồi & Đánh giá:**
  > AI đã hệ thống hóa bảng đánh giá đầy đủ 16 chức năng phân theo từng vai trò (Khách vãng lai, Học viên, Giảng viên, Quản trị viên), đồng thời thiết kế kịch bản Demo 7 bước khép kín có tính kiểm chứng chéo giữa các phân hệ (khóa tài khoản tại trang Admin và kiểm tra chặn đăng nhập ngay tại trang Login; duyệt/ẩn khóa học tại Admin và đối chiếu hiển thị tức thì bên trang `/courses`), kèm bảng phân công nhiệm vụ cụ thể cho từng thành viên.

- **Bài học rút ra**: Kịch bản Demo nên xây dựng theo luồng dữ liệu liên thông giữa các vai trò để làm nổi bật tính toàn vẹn CSDL và phân quyền bảo mật middleware, đồng thời cần đối chiếu kỹ bảng phân công để đảm bảo đầy đủ đầu việc của tất cả thành viên.
## AI-08: Triển khai và gỡ lỗi chức năng Hồ sơ cá nhân & Kết quả học tập (HT-07)
- **Thời gian:** 21:30 - 22:30 (Thứ 5, Ngày 19/11)
- **Công cụ AI:** Gemini
- **Prompt:**
  > "Xây dựng chức năng Hồ sơ cá nhân và Kết quả học tập (HT-07) theo kiến trúc 3 lớp (Controller - Service - Repository) cho phép sửa thông tin, đổi mật khẩu và xem lịch sử điểm thi; đồng thời khắc phục lỗi thiếu Model QuizAttempt, lỗi 404 tại /profile, lỗi thiếu view layouts.app và gắn liên kết trang hồ sơ vào tên người dùng trên thanh điều hướng."
- **Kết quả AI phản hồi & Đánh giá:**
  > AI đã cung cấp đầy đủ mã nguồn cho `StudentProfileController`, `StudentProfileService`, `StudentProfileRepository` và giao diện `student/profile.blade.php` tích hợp cập nhật thông tin cá nhân, đổi mật khẩu băm chuẩn Bcrypt và bảng lịch sử làm bài trắc nghiệm. Khi đối chiếu cấu trúc bảng `quiz_attempts` thực tế trong CSDL `doan_quanlyhoctap` (liên kết trực tiếp qua `course_id` thay vì bảng trung gian `quizzes`), AI đã điều chỉnh lại Model `QuizAttempt` và truy vấn Eloquent cho khớp đúng các cột dữ liệu. Ngoài ra, AI hỗ trợ xử lý dứt điểm lỗi `404 Not Found` do đặt nhầm route `/profile` vào trong nhóm tiền tố `admin`, bổ sung layout `layouts/app.blade.php` và gắn liên kết chuyển hướng `/profile` vào tên tài khoản ngay cạnh nút Đăng xuất.
- **Bài học rút ra:** Cần kiểm tra cấu trúc bảng và khóa ngoại thực tế trong CSDL trước khi định nghĩa quan hệ Model, đồng thời rà soát kỹ phạm vi nhóm `prefix` và `middleware` trong `routes/web.php` khi tích hợp route của nhiều vai trò.

---

## AI-09: Hoàn thiện luồng Ghi danh, Khóa học của tôi và điều hướng vào bài giảng (HT-04)
- **Thời gian:** 22:35 - 23:20 (Thứ 5, Ngày 19/11)
- **Công cụ AI:** Gemini
- **Prompt:**
  > "Khắc phục lỗi MethodNotAllowedHttpException (POST /courses/2) khi bấm nút ghi danh chuyển tiếp sang học bài giảng (HT-04) trên trang chi tiết khóa học, bổ sung xử lý lưu ghi danh vào bảng enrollments và hoàn thiện trang Khóa học của tôi (/my-courses) để học viên truy cập vào học."
- **Kết quả AI phản hồi & Đánh giá:**
  > AI đã xác định đúng nguyên nhân lỗi `Method Not Allowed` xuất phát từ việc thiếu route `courses.enroll` khiến thuộc tính `action` của biểu mẫu trên `courses/show.blade.php` bị gán thành `#`, từ đó bổ sung định tuyến `POST /courses/{course}/enroll` và phương thức `enroll()` trong `CourseController` (có kiểm tra chống ghi danh trùng lặp và cập nhật sĩ số học viên). Tiếp đó, AI hỗ trợ xử lý lỗi `404 Not Found` tại `/my-courses` bằng cách bổ sung phương thức `myCourses()` truy vấn bảng `enrollments` cùng giao diện `courses/my-courses.blade.php` đồng bộ header/footer của nhóm, giúp học viên chuyển mạch liền mạch từ danh sách khóa học đã đăng ký sang trang học bài giảng (`HT-04`).
- **Bài học rút ra:** Khi sử dụng điều kiện `Route::has(...)` trên giao diện Blade, cần đảm bảo toàn bộ tên route liên kết giữa các phân hệ (ghi danh, danh sách khóa học cá nhân, vào học) đã được khai báo đầy đủ trong `routes/web.php` và làm mới bộ nhớ đệm định tuyến (`php artisan route:clear`).