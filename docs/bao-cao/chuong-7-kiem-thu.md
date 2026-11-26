# CHƯƠNG 7: KIỂM THỬ HỆ THỐNG VÀ KIỂM THỬ CHÉO

## 7.1. Mục tiêu và phạm vi kiểm thử
Quá trình kiểm thử nhằm đảm bảo toàn bộ 12 chức năng bắt buộc (`HT-01` đến `HT-15`) và các yêu cầu phi chức năng (`HT-NFR1` đến `HT-NFR4`) của nền tảng **EduPlatform – Quản lý học tập** vận hành ổn định, đúng nghiệp vụ, phân quyền chặt chẽ và bảo mật dữ liệu trước khi đóng gói sản phẩm.

Các loại hình kiểm thử đã thực hiện:
- **Kiểm thử chức năng (Functional Testing):** Kiểm tra luồng nghiệp vụ của 3 vai trò Học viên (`Student`), Giảng viên (`Teacher`) và Quản trị viên (`Admin`).
- **Kiểm thử bảo mật & phân quyền (Security & Authorization Testing):** Kiểm tra chặn truy cập chéo khóa học giữa các giảng viên, chặn truy cập bài học khi chưa ghi danh, kiểm tra chống lộ đáp án bài trắc nghiệm ở phía trình duyệt (`View Source` / `DevTools Network`).
- **Kiểm thử kiểm chứng dữ liệu & tệp tin (Validation & File Upload Testing):** Kiểm tra ràng buộc loại tệp (`.pdf`) và giới hạn dung lượng tối đa (`5MB`) khi tải tài liệu bài giảng.
- **Kiểm thử giao diện đa thiết bị (Responsive Testing):** Kiểm tra hiển thị trên màn hình máy tính và thiết bị di động (`360px`).
- **Kiểm thử chéo (Cross-testing):** Thực hiện kiểm thử độc lập trên sản phẩm của nhóm đối tác và tiếp nhận phản hồi lỗi từ nhóm bạn.

## 7.2. Kết quả kiểm thử nội bộ (31 Test Cases)
Nhóm đã xây dựng và thực thi bộ **31 kịch bản kiểm thử (Test Cases)** lưu tại `docs/test-cases.md`, bao phủ toàn bộ các phân hệ và yêu cầu phi chức năng:

| Nhóm chức năng / Phân hệ | Số lượng Test Case | Số lượng Đạt (Pass) | Số lượng Lỗi (Fail) | Tỷ lệ đạt |
| :--- | :---: | :---: | :---: | :---: |
| Xác thực & Phân quyền (`HT-01` – `HT-03`) | 6 | 6 | 0 | 100% |
| Ghi danh, Học bài & Tiến độ (`HT-04`, `HT-05`) | 6 | 6 | 0 | 100% |
| Trắc nghiệm tự động & Lịch sử thi (`HT-06`, `HT-07`) | 6 | 6 | 0 | 100% |
| Quản lý khóa học & Bài học Giảng viên (`HT-09`, `HT-10`) | 5 | 5 | 0 | 100% |
| Hỏi đáp khóa học (`HT-11`) | 3 | 3 | 0 | 100% |
| Quản trị người dùng & Khóa học Admin (`HT-14`, `HT-15`) | 3 | 3 | 0 | 100% |
| Phi chức năng & Bảo mật (`HT-NFR1` – `HT-NFR4`) | 2 | 2 | 0 | 100% |
| **Tổng cộng** | **31** | **31** | **0** | **100%** |

## 7.3. Kết quả kiểm thử chéo (Cross-testing)
Trong buổi kiểm thử chéo (Ngày 11), nhóm đã tiến hành kiểm thử độc lập trên sản phẩm của nhóm đối tác (`duchoa444/DoAnWeb_QuanLyHocTap`) theo bộ kịch bản kiểm thử và báo cáo công khai **6 Issues** lên GitHub của nhóm bạn:

| STT | Mã Issue | Mô tả lỗi phát hiện | Phân hệ | Mức độ | Trạng thái xử lý |
| :---: | :---: | :--- | :--- | :---: | :---: |
| 1 | `#1` | Bảng lịch sử các lần làm bài tại trang `/exam-history` bị lặp 2 hàng tiêu đề (`thead`) và lệch thứ tự cột hiển thị | Student (`HT-07`) | Trung bình | Đã báo cáo (Open) |
| 2 | `#2` | Giao diện làm bài trắc nghiệm (`/courses/1/quiz/start`) bị lặp tiền tố số thứ tự câu hỏi (`"Câu 1: Cau 1: ..."`) | Student (`HT-06`) | Nhỏ | Đã báo cáo (Open) |

| 6 | `#3` | Thông báo `"Đăng ký tài khoản thành công!"` bị hiển thị lặp 2 lần tại trang danh sách khóa học (`/courses`) sau khi đăng ký | Auth (`HT-03`) | Nhỏ | Đã báo cáo (Open) |


## 7.4. Thống kê tiếp nhận và khắc phục lỗi từ nhóm bạn
Các lỗi do nhóm đối tác phản hồi trên repository của nhóm đã được QA (`TV5`) chuyển thành thẻ `[BUG]` trên bảng Trello, phân công đúng thành viên phụ trách chức năng và đóng hoàn toàn trước thời điểm chốt báo cáo:

| STT | Mã Issue | Nội dung lỗi tiếp nhận | Chức năng | Người phụ trách | Nhánh / Commit xử lý | Trạng thái |
| :---: | :---: | :--- | :--- | :---: | :--- | :---: |
| 1 | `#1` | Lặp 2 dòng tiêu đề bảng lịch sử làm bài và lệch cột | `HT-07` | TV1 | `fix/...` (`Closes #1`) | Đã sửa (Closed) |
| 2 | `#2` | Lặp tiền tố số thứ tự câu hỏi ("Câu 1: Cau 1:") khi làm trắc nghiệm | `HT-06` | TV2 | `fix/...` (`Closes #1`) | Đã sửa (Closed) |
| 3 | `#3` | Lặp 2 lần thông báo "Đăng ký tài khoản thành công!" ở trang khóa học | `HT-03` | TV2, TV3 | `fix/...` (`Closes #3`) | Đã sửa (Closed) |