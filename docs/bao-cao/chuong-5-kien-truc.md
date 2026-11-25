# CHƯƠNG 5. THIẾT KẾ KIẾN TRÚC VÀ PHÂN QUYỀN HỆ THỐNG

## 5.1. Kiến trúc tổng thể hệ thống
Hệ thống Quản lý học tập trực tuyến (`DoAnWeb_QuanLyHocTap`) được xây dựng trên nền tảng **PHP Laravel** theo mô hình kiến trúc phân lớp kết hợp **MVC (Model – View – Controller)** và **Service Layer**:
- **Presentation Layer (Blade Views):** Hiển thị giao diện người dùng tách biệt theo 3 nhóm đối tượng (Học viên, Giảng viên, Quản trị viên) và tích hợp đồng hồ đếm ngược phía client.
- **Routing & Middleware Layer:** Tiếp nhận HTTP Request, xác thực phiên đăng nhập (`auth`) và kiểm tra vai trò truy cập trước khi chuyển vào Controller.
- **Controller Layer (`AuthController`, `QuizController`,...):** Điều phối luồng dữ liệu, validate đầu vào cơ bản và trả về View hoặc JSON tương ứng.
- **Service Layer (`AuthService`, `QuizService`,...):** Đóng gói toàn bộ nghiệp vụ cốt lõi (băm mật khẩu, kiểm tra giới hạn 3 lượt thi, kiểm tra thời gian server-side, chấm điểm tự động) giúp Controller gọn nhẹ và dễ viết Unit Test độc lập.
- **Data Access Layer (Eloquent ORM & Query Builder):** Giao tiếp với CSDL MySQL, đảm bảo toàn vẹn dữ liệu và chống SQL Injection.

*(Đính kèm sơ đồ kiến trúc: `docs/architecture.png`)*

## 5.2. Thiết kế phân quyền 3 vai trò (RBAC)
Hệ thống phân chia người dùng thành 3 vai trò chính thông qua thuộc tính `role` trong bảng `users`:
1. **Học viên (`student`):** Vai trò mặc định khi đăng ký tài khoản mới. Được phép xem danh sách khóa học, ghi danh học tập, học bài giảng và làm bài trắc nghiệm tối đa 3 lượt/khóa học.
2. **Giảng viên (`instructor` / `teacher`):** Được quản lý khóa học của chính mình, biên soạn nội dung bài học và ngân hàng câu hỏi trắc nghiệm.
3. **Quản trị viên (`admin`):** Có toàn quyền quản lý người dùng (khóa/mở khóa tài khoản), kiểm duyệt khóa học và giám sát toàn hệ thống.

### Cơ chế thực thi bảo mật & điều hướng:
- **Xác thực (`AuthService`):** Mật khẩu được băm một chiều bằng `Hash::make()`. Khi đăng nhập, hệ thống kiểm tra trạng thái tài khoản; nếu tài khoản đang bị khóa sẽ từ chối cấp phiên làm việc (Session) và trả về thông báo lỗi tiếng Việt.
- **Middleware kiểm soát truy cập:** Các nhóm route `/teacher/*` chỉ cho phép vai trò giảng viên truy cập; các nhóm route `/admin/*` chỉ cho phép quản trị viên truy cập. Mọi truy cập trái phép đều bị chặn (HTTP 403 hoặc chuyển hướng về trang chủ).
- **Điều hướng sau đăng nhập:** Dựa trên `role` của người dùng để điều hướng tự động đến đúng không gian làm việc (`/admin/dashboard`, `/teacher/courses`, hoặc trang khóa học của học viên).