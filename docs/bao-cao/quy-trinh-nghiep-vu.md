## Quy trình nghiệp vụ tổng thể của hệ thống Quản lý khóa học

Dựa trên cấu trúc cơ sở dữ liệu gồm 9 bảng nghiệp vụ chính (`users`, `categories`, `courses`, `lessons`, `enrollments`, `lesson_progress`, `questions`, `quiz_attempts`, `lesson_comments`), luồng hoạt động của hệ thống được vận hành khép kín qua 4 giai đoạn theo 3 vai trò:

### Giai đoạn 1: Quản trị hệ thống & Phân quyền (`admin`)
1. **Quản lý danh mục (`categories`):** Quản trị viên khởi tạo và phân loại các danh mục khóa học (`name`, `description`).
2. **Quản lý tài khoản & Phân quyền (`users`):** Quản trị viên cấp tài khoản giảng viên (`role = 'teacher'`), quản lý danh sách học viên (`role = 'student'`) và kiểm soát trạng thái hoạt động qua cột `is_active` (khóa/mở khóa tài khoản vi phạm).
3. **Kiểm duyệt khóa học (`courses`):** Giám sát trạng thái hiển thị của khóa học (`draft` / `published`).

### Giai đoạn 2: Biên soạn & Xuất bản khóa học (`teacher`)
1. **Khởi tạo khóa học (`courses`):** Giảng viên (`teacher_id`) tạo khóa học mới gắn với danh mục (`category_id`), thiết lập tiêu đề, mô tả, ảnh bìa (`thumbnail`), thời lượng thi (`quiz_minutes`) ở trạng thái nháp (`status = 'draft'`).
2. **Soạn bài giảng (`lessons`):** Thêm các bài học sắp xếp theo thứ tự (`position`), hỗ trợ nội dung văn bản (`content`), video (`video_url`) và tài liệu PDF (`pdf_path`).
3. **Tạo ngân hàng câu hỏi (`questions`):** Biên soạn câu hỏi trắc nghiệm 4 lựa chọn (`option_a` đến `option_d`), đáp án đúng (`correct_option`) và điểm số (`points`).
4. **Xuất bản:** Chuyển trạng thái khóa học sang `status = 'published'` để học viên có thể ghi danh.

### Giai đoạn 3: Ghi danh, Học tập & Tương tác (`student`)
1. **Ghi danh khóa học (`enrollments`):** Học viên đăng ký vào khóa học đã xuất bản; hệ thống đảm bảo mỗi học viên chỉ ghi danh 1 lần trên mỗi khóa (`unique(user_id, course_id)`).
2. **Học tập & Tính tiến độ (`lesson_progress`):** Hệ thống ghi nhận trạng thái hoàn thành từng bài học (`status`, `completed_at`) và tính phần trăm tiến độ hoàn thành khóa học.
3. **Thảo luận bài học (`lesson_comments`):** Học viên và giảng viên trao đổi hỏi đáp theo cấu trúc cây bình luận cha – con (`parent_id`).

### Giai đoạn 4: Kiểm tra trắc nghiệm & Chấm điểm tự động (`quiz_attempts`)
1. **Khởi tạo lượt thi:** Kiểm tra điều kiện đã ghi danh và chưa vượt quá **3 lượt thi/khóa học**; lưu mốc `started_at` và ẩn `correct_option` khi trả đề về giao diện.
2. **Nộp bài & Chấm điểm:** Kiểm tra ràng buộc thời gian (`started_at + quiz_minutes`), lưu bài làm dưới dạng JSON (`answers`), đối chiếu đáp án trên server để tính điểm thang 10 (`score`) và cập nhật điểm cao nhất.