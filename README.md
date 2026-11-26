# EduPlatform – Hệ thống Quản lý Học tập Trực tuyến (LMS)

Nền tảng khóa học trực tuyến miễn phí cho phép **Giảng viên** biên soạn bài học và ngân hàng câu hỏi trắc nghiệm, **Học viên** ghi danh học tập, làm bài trắc nghiệm chấm điểm tự động và thảo luận hỏi đáp, **Quản trị viên** quản lý tài khoản và kiểm duyệt khóa học.

- **Bảng quản lý tiến độ (Trello):** `[https://trello.com/invite/b/6af11d2c137d9527eed20231/ATTI18730a917052610a3d34afa7d315a3c876847619/nhomvhhhnquan-ly-hoc-tap]`
- **Video demo sản phẩm (3–5 phút):** `[https://www.youtube.com/watch?v=9-1mepMuXkg]`
- **Tài liệu báo cáo & Kiểm thử:** Thư mục `docs/bao-cao/` và `docs/test-cases.md`

---

## 1. Thành viên nhóm & Phân công chức năng

| Thành viên | Vai trò | Mã chức năng phụ trách | Tài liệu & Nhiệm vụ bổ sung |
| :--- | :--- | :--- | :--- |
| **TV1** | Trưởng nhóm (PM / Scrum Master) | `HT-07`, `HT-14`, `HT-15` | Chương 1, 2, 9, 10; Ghép báo cáo & Slide |
| **TV2** | Backend Lead | `HT-03`, `HT-06` | Chương 5 (Kiến trúc), Chương 6 (Thuật toán chấm thi) |
| **TV3** | Frontend Lead | `HT-01`, `HT-02`, `HT-05` | Chương 5 (Giao diện); Quay & dựng Video demo |
| **TV4** | Cơ sở dữ liệu (Database) | `HT-09`, `HT-10` | Chương 3 (Công nghệ), Chương 5 (CSDL & ERD); Dữ liệu mẫu |
| **TV5** | Git & Kiểm thử (QA) | `HT-04`, `HT-11` | Chương 7 (Kiểm thử), Chương 8 (Git); `README.md` final |

---

## 2. Công nghệ sử dụng

- **Backend:** PHP 8.2+, Laravel Framework 11.x
- **Frontend:** Blade Template, Bootstrap 5, JavaScript
- **Cơ sở dữ liệu:** SQLite (mặc định chạy nhanh trên máy sạch) hoặc MySQL 8.0+
- **Quản lý mã nguồn & Tiến độ:** Git, GitHub, Trello

---

## 3. Hướng dẫn cài đặt trên máy sạch (≤ 15 phút)

### Yêu cầu môi trường
- Đã cài đặt **PHP ≥ 8.2** (bật các extension: `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml`, `ctype`, `json`).
- Đã cài đặt **Composer** (≥ 2.x) và **Git**.

### Các bước cài đặt & khởi chạy

**Bước 1: Clone mã nguồn từ GitHub**
    git clone https://github.com/duchoa444/DoAnWeb_QuanLyHocTap.git
    cd DoAnWeb_QuanLyHocTap

**Bước 2: Cài đặt các gói thư viện PHP**
    composer install

**Bước 3: Tạo tệp cấu hình môi trường `.env` và khóa ứng dụng**
- Trên Windows (Command Prompt / PowerShell):
      copy .env.example .env
      php artisan key:generate
- Trên macOS / Linux:
      cp .env.example .env
      php artisan key:generate

**Bước 4: Khởi tạo Cơ sở dữ liệu & Nạp dữ liệu mẫu (Migration + Seed)**
- Nếu dùng **SQLite** (khuyên dùng để chạy nhanh trong 1 phút): đảm bảo tệp `database/database.sqlite` tồn tại (tạo tệp trống nếu chưa có) và giữ `DB_CONNECTION=sqlite` trong `.env`.
- Chạy lệnh tạo bảng và nạp dữ liệu mẫu (có thể chạy lại nhiều lần để reset dữ liệu sạch):
      php artisan migrate:fresh --seed

**Bước 5: Liên kết thư mục lưu trữ tệp tải lên (PDF tài liệu bài học)**
    php artisan storage:link

**Bước 6: Khởi chạy máy chủ phát triển**
    php artisan serve

Truy cập ứng dụng trên trình duyệt tại địa chỉ: **`http://127.0.0.1:8000`**

---

## 4. Tài khoản Demo 3 vai trò

Sau khi chạy lệnh `php artisan migrate:fresh --seed`, hệ thống có sẵn dữ liệu mẫu (2 khóa học hoàn chỉnh bài học, tài liệu PDF và ngân hàng câu hỏi trắc nghiệm) cùng các tài khoản kiểm thử sau *(đối chiếu lại email/mật khẩu chính xác trong `database/seeders/DatabaseSeeder.php`)*:

| Vai trò | Email đăng nhập | Mật khẩu | Quyền hạn chính |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin@example.com` | `12345678` | Quản lý người dùng, tạo tài khoản giảng viên, kiểm duyệt khóa học |
| **Giảng viên (Teacher)** | `teacher1@example.com` | `12345678` | Tạo/sửa khóa học, soạn bài học, tải tài liệu PDF (≤ 5MB), tạo câu hỏi trắc nghiệm |
| **Học viên (Student)** | `student1@example.com` | `12345678` | Ghi danh khóa học, học bài, làm trắc nghiệm chấm điểm tự động, xem lịch sử thi, hỏi đáp |

---

## 5. Hướng dẫn chạy kiểm thử (Testing)

### 5.1. Chạy kiểm thử tự động (Automated Tests)
Chạy bộ kiểm thử tích hợp của Laravel bằng lệnh:
    php artisan test

### 5.2. Kiểm thử thủ công theo kịch bản (31 Test Cases)
Toàn bộ 31 kịch bản kiểm thử chức năng, phân quyền và bảo mật (`HT-01` đến `HT-15`, `HT-NFR1` đến `HT-NFR4`) được lưu chi tiết tại tệp:
- `docs/test-cases.md`

---

## 6. Cấu trúc thư mục chính

    DoAnWeb_QuanLyHocTap/
    ├── ai-logs/                  # Nhật ký sử dụng AI và phản tư của 5 thành viên
    ├── app/
    │   ├── Http/Controllers/     # Xử lý nghiệp vụ Student, Teacher, Admin, Auth
    │   ├── Models/               # User, Course, Lesson, Quiz, Question, Enrollment...
    │   └── Policies/             # Phân quyền sở hữu khóa học và truy cập bài học
    ├── database/
    │   ├── migrations/           # Định nghĩa cấu trúc các bảng CSDL
    │   └── seeders/              # Dữ liệu mẫu sạch (DatabaseSeeder.php)
    ├── docs/
    │   ├── bao-cao/              # Bản nháp các chương báo cáo (Chương 1 - Chương 10)
    │   └── test-cases.md         # Bộ 31 kịch bản kiểm thử hệ thống
    ├── resources/views/          # Giao diện Blade (Auth, Student, Teacher, Admin)
    └── routes/web.php            # Định tuyến toàn bộ chức năng hệ thống