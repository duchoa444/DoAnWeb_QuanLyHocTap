# DÀN Ý BÁO CÁO ĐỒ ÁN: HỆ THỐNG QUẢN LÝ HỌC TẬP TRỰC TUYẾN (EDUPLATFORM)

## BẢNG PHÂN CÔNG THÀNH VIÊN

| Thành viên | Vai trò | Phụ trách nội dung |
| :--- | :--- | :--- |
| **TV1** | Trưởng nhóm (PM / Scrum Master) | Chương 1, Chương 2, Chương 4, Chương 9, Chương 10 |
| **TV2** | Backend & Kiến trúc hệ thống | Chương 5 (Kiến trúc), Chương 6 |
| **TV3** | Frontend & Truyền thông | Chương 5 (Giao diện), Video demo sản phẩm |
| **TV4** | Phân tích & Cơ sở dữ liệu | Chương 3, Chương 5 (Cơ sở dữ liệu) |
| **TV5** | Kiểm thử (QA/QC) & Đánh giá | Chương 7, Chương 8 |

---

## CHI TIẾT CÁC CHƯƠNG

### Chương 1: Tổng quan đề tài *(Phụ trách: TV1)*
- **1.1. Đặt vấn đề & Bối cảnh:** Nhu cầu học tập trực tuyến và số hóa quy trình quản lý đào tạo.
- **1.2. Mục tiêu đề tài:** Xây dựng nền tảng học tập trực tuyến hỗ trợ 3 nhóm người dùng (Học viên, Giảng viên, Quản trị viên).
- **1.3. Đối tượng & Phạm vi nghiên cứu:**
  - *Học viên:* Tìm kiếm, đăng ký khóa học, học bài giảng, làm bài thi trắc nghiệm, hỏi đáp trong bài học, xem lịch sử thi.
  - *Giảng viên:* Tạo/quản lý khóa học, biên soạn câu hỏi trắc nghiệm, theo dõi kết quả thi (xuất CSV), giải đáp thắc mắc học viên.
  - *Quản trị viên:* Quản lý tài khoản & phân quyền, quản lý danh mục, kiểm duyệt/ẩn khóa học.

### Chương 2: Khảo sát và Phân tích yêu cầu *(Phụ trách: TV1)*
- **2.1. Quy trình nghiệp vụ hệ thống:** Luồng tạo & duyệt khóa học; luồng ghi danh, học tập & thi cuối khóa; luồng hỏi đáp bài giảng.
- **2.2. Yêu cầu chức năng (12 chức năng cốt lõi & mở rộng):**
  - Phân hệ chung: Đăng ký, Đăng nhập/Đăng xuất, Phân quyền RBAC (`student`, `teacher`, `admin`).
  - Phân hệ Học viên: Xem danh sách & chi tiết khóa học, Ghi danh, Học bài & đánh dấu hoàn thành, Làm bài trắc nghiệm tính giờ, Xem điểm & lịch sử thi, Hỏi đáp dưới bài giảng.
  - Phân hệ Giảng viên: CRUD khóa học & bài giảng, Quản lý ngân hàng câu hỏi & thời lượng thi, Thống kê đề thi & xuất báo cáo CSV, Quản lý hỏi đáp học viên.
  - Phân hệ Quản trị viên: Quản lý người dùng (khóa/mở tài khoản), Quản lý danh mục khóa học, Duyệt/Ẩn khóa học.
- **2.3. Yêu cầu phi chức năng:** Bảo mật (mã hóa mật khẩu, chống CSRF/SQL Injection), hiệu năng phản hồi, giao diện trực quan dễ sử dụng.

### Chương 3: Phân tích và Thiết kế hệ thống *(Phụ trách: TV4)*
- **3.1. Biểu đồ Use Case tổng quát.**
- **3.2. Biểu đồ Use Case chi tiết & Đặc tả Use Case** cho từng tác nhân (Student, Teacher, Admin).
- **3.3. Biểu đồ hoạt động (Activity Diagram):** Quy trình làm bài thi trắc nghiệm; Quy trình kiểm duyệt khóa học.
- **3.4. Biểu đồ tuần tự (Sequence Diagram):** Đăng nhập & phân quyền; Nộp bài thi & chấm điểm tự động; Gửi & phản hồi hỏi đáp.

### Chương 4: Công nghệ và Môi trường phát triển *(Phụ trách: TV1)*
- **4.1. Ngôn ngữ & Framework:** PHP 8.2+, Laravel 12.
- **4.2. Hệ quản trị cơ sở dữ liệu:** MySQL.
- **4.3. Giao diện người dùng:** Blade Template Engine, HTML5, CSS3, JavaScript.
- **4.4. Công cụ quản lý & phát triển:** VS Code, Git/GitHub, Trello, XAMPP/Laragon.

### Chương 5: Thiết kế Kiến trúc, Cơ sở dữ liệu và Giao diện *(Phụ trách: TV2, TV3, TV4)*
- **5.1. Thiết kế kiến trúc hệ thống *(TV2)*:**
  - Mô hình kiến trúc MVC trong Laravel 12.
  - Tổ chức Routing (`web.php`), nhóm tiền tố (`teacher.*`, `admin.*`) và cơ chế lọc truy cập (`Auth`, `RoleMiddleware`).
- **5.2. Thiết kế cơ sở dữ liệu *(TV4)*:**
  - Sơ đồ thực thể kết hợp (ERD) và lược đồ quan hệ.
  - Bảng dữ liệu chi tiết: `users`, `categories`, `courses`, `lessons`, `lesson_completions`, `enrollments`, `questions`, `quiz_attempts`, `lesson_comments`.
- **5.3. Thiết kế giao diện người dùng *(TV3)*:**
  - Sơ đồ cây giao diện (Sitemap) theo từng vai trò.
  - Thiết kế giao diện các màn hình chính (Trang danh sách khóa học, Trang học bài & Hỏi đáp, Màn hình làm bài trắc nghiệm, Dashboard Giảng viên & Quản trị viên).

### Chương 6: Cài đặt và Triển khai chức năng *(Phụ trách: TV2)*
- **6.1. Cấu trúc mã nguồn dự án Laravel 12.**
- **6.2. Cài đặt các nhóm chức năng trọng tâm:**
  - Xác thực và phân quyền người dùng.
  - Quản lý khóa học, upload ảnh bìa (giới hạn 5MB) và kiểm duyệt nội dung.
  - Hệ thống thi trắc nghiệm tự động chấm điểm & xuất báo cáo CSV (UTF-8 BOM).
  - Hệ thống hỏi đáp 2 cấp trong bài học.
- **6.3. Các biện pháp bảo mật & xử lý ngoại lệ:** Form Request Validation, Eloquent ORM, CSRF Token, phân quyền sở hữu dữ liệu.

### Chương 7: Kiểm thử hệ thống *(Phụ trách: TV5)*
- **7.1. Kế hoạch và phương pháp kiểm thử:** Kiểm thử chức năng (Black-box), kiểm thử phân quyền, kiểm thử chéo.
- **7.2. Bộ kịch bản kiểm thử (Test Cases):** Bảng test case chi tiết cho Học viên, Giảng viên, Quản trị viên.
- **7.3. Tổng hợp kết quả kiểm thử & Khắc phục lỗi:** Thống kê tỷ lệ Pass/Fail và các lỗi đã xử lý trong quá trình phát triển.

### Chương 8: Đánh giá kết quả và Hướng phát triển *(Phụ trách: TV5)*
- **8.1. Kết quả đạt được:** Mức độ hoàn thiện 12/12 chức năng bắt buộc và các tính năng mở rộng.
- **8.2. Hạn chế còn tồn tại.**
- **8.3. Hướng phát triển trong tương lai:** Tích hợp thanh toán trực tuyến, cấp chứng chỉ tự động (PDF), thông báo thời gian thực (Realtime Notification).

### Chương 9: Quản lý dự án và Làm việc nhóm *(Phụ trách: TV1)*
- **9.1. Quy trình làm việc Agile/Scrum:** Chia Sprint, họp đứng (Daily Standup), rà soát cuối ngày.
- **9.2. Quản lý công việc trên Trello:** Bảng Kanban (`To Do`, `Doing`, `Review`, `Done`).
- **9.3. Quy trình quản lý mã nguồn Git:** Chiến lược phân nhánh (`main`, `develop`, `feature/*`, `fix/*`, `docs/*`), quy tắc commit và xử lý xung đột (Merge Conflict).

### Chương 10: Kết luận và Phụ lục *(Phụ trách: TV1)*
- **10.1. Kết luận chung.**
- **10.2. Tài liệu tham khảo.**
- **10.3. Phụ lục:** Nhật ký sử dụng công cụ AI hỗ trợ phát triển dự án (AI-Log) & Kịch bản Video demo *(phối hợp TV3)*.