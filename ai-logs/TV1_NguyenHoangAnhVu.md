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