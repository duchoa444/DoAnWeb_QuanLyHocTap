# NHẬT KÝ SỬ DỤNG AI – Phan Duc Hoa (TV4 – DATABASE)

## AI-01: Phác thảo sơ đồ ERD

- **Thời gian:** 09/11/2026
- **Công cụ AI:** ChatGPT
- **Prompt:** Hướng dẫn thiết kế cơ sở dữ liệu gồm 8 bảng và cách vẽ ERD bằng dbdiagram.io.
- **Kết quả AI phản hồi & Đánh giá:**
  - **Gợi ý giữ lại:** Danh sách 8 bảng, PK, FK và các mối quan hệ giữa bảng.
  - **Gợi ý loại bỏ:** Những thuộc tính chưa phù hợp với yêu cầu đồ án.
- **Bài học rút ra:** Biết cách xác định bảng, khóa chính, khóa ngoại và tạo bản phác ERD bằng dbdiagram.io.

-Cập nhật lại ERD theo chuẩn 3NF

**Thời gian:** 11/11/2026
- **Công cụ AI:** ChatGPT
- **Prompt:** Thiết kế CSDL cập nhật bảng ERD_v2 bằng dbdiagram.io (đã cập nhật 5 bảng)


## NGÀY 03 – THỨ TƯ, TUẦN 1

AI-03-01: Hỗ trợ thiết kế Migration cơ sở dữ liệu

Công cụ: ChatGPT

Nội dung yêu cầu: Hướng dẫn kiểm tra cấu trúc các migration cho hệ thống quản lý học tập Laravel, bao gồm các bảng categories, courses, lessons, enrollments, lesson_progress, questions và quiz_attempts.

Kết quả AI đề xuất: Giải thích cách thiết lập khóa chính, khóa ngoại, kiểu dữ liệu, quan hệ giữa các bảng và các ràng buộc UNIQUE cần thiết.

Áp dụng: Sử dụng nội dung tham khảo để kiểm tra và hoàn thiện 7 migration của dự án.

Nội dung yêu cầu: Hướng dẫn tạo dữ liệu mẫu cho hệ thống quản lý học tập bằng Laravel Seeder.

Kết quả AI đề xuất: Hướng dẫn tạo dữ liệu mẫu cho danh mục, giảng viên, quản trị viên, học viên, khóa học, bài học, câu hỏi, đăng ký khóa học và lượt làm bài kiểm tra.

## NGÀY 04 – THỨ NĂM, TUẦN 1

 AI-04-01: Đối chiếu ERD với Migration

Công cụ: ChatGPT

Nội dung yêu cầu: Kiểm tra sơ đồ ERD của dự án quản lý học tập và đối chiếu với cấu trúc database đã triển khai.

Kết quả AI đề xuất: Chỉ ra các thông tin cần rà soát như tên cột, khóa chính, khóa ngoại, quan hệ giữa các bảng, timestamps và các ràng buộc UNIQUE.

Áp dụng: Điều chỉnh sơ đồ ERD để thống nhất với cấu trúc database, sau đó xuất sơ đồ ERD_Final.png.

Áp dụng: Hoàn thiện tài liệu mô tả 8 bảng trong file mo-ta-bang.md và chuẩn bị nội dung trình bày ERD.

## Ngày 5 – Trình bày và chỉnh sửa ERD
 Tìm hiểu và chuẩn hóa ERD theo 3NF
- **Công cụ:** ChatGPT
- **Nội dung hỏi:** Mô hình ERD chuẩn hóa 3NF là gì và áp dụng như thế nào vào đề tài Quản lý học tập?
- **AI hỗ trợ:** Giải thích nguyên tắc chuẩn hóa 3NF và gợi ý cấu trúc các bảng chính như users, categories, courses, lessons, enrollments, lesson_progress, questions và quiz_attempts.
- **Áp dụng:** Dựa trên gợi ý để xem xét lại quan hệ giữa các bảng và xây dựng ERD phù hợp với nghiệp vụ của hệ thống.
- **Kết quả:** Có cơ sở để chỉnh sửa và trình bày ERD trong Mốc 1.

## Ngày 6 – HT-09: Quản lý khóa học giảng viên

 Hỗ trợ xây dựng chức năng quản lý khóa học (HT-09)
- **Công cụ:** ChatGPT
- **Nội dung hỏi:** Hướng dẫn triển khai chức năng CRUD khóa học dành cho giảng viên trong dự án Laravel.
- **AI hỗ trợ:** Hỗ trợ phân tích cách tổ chức xử lý chức năng quản lý khóa học và kiểm tra các thao tác thêm, sửa, xóa.
- **Áp dụng:** Triển khai phần quản lý khóa học trong dự án, chú ý việc giảng viên chỉ được thao tác với khóa học thuộc quyền sở hữu của mình.
- **Kết quả:** Có phần chức năng HT-09 để tiếp tục kiểm tra và hoàn thiện.

## Ngày 7 – HT-10: Quản lý bài học

Hỗ trợ triển khai chức năng quản lý bài học (HT-10)
- **Công cụ:** ChatGPT
- **Nội dung hỏi:** Hỗ trợ xây dựng chức năng quản lý bài học trong khóa học bằng Laravel.
- **AI hỗ trợ:** Hướng dẫn cách tổ chức các phần xử lý liên quan đến bài học và các thao tác thêm, sửa, xóa, sắp xếp.
- **Áp dụng:** Triển khai các phần xử lý bài học và giao diện quản lý bài học của giảng viên.
- **Kết quả:** Xây dựng được các phần chính của HT-10 và tiếp tục kiểm tra hoạt động của chức năng.

## Ngày 9 – Kiểm tra quyền sở hữu và dữ liệu demo

 Hỗ trợ chuẩn bị dữ liệu demo cho hệ thống
- **Công cụ:** ChatGPT
- **Nội dung hỏi:** Hướng dẫn tạo dữ liệu demo cho hệ thống Quản lý học tập, gồm khóa học, bài học, câu hỏi và tài khoản theo các vai trò.
- **AI hỗ trợ:** Gợi ý cách tổ chức dữ liệu trong DatabaseSeeder và liên kết dữ liệu giữa các bảng.
- **Áp dụng:** Xây dựng dữ liệu mẫu phục vụ kiểm tra hệ thống, chú ý các quan hệ giữa khóa học, bài học, câu hỏi và tài khoản.
- **Kết quả:** Có dữ liệu demo để phục vụ việc kiểm tra và trình diễn hệ thống.