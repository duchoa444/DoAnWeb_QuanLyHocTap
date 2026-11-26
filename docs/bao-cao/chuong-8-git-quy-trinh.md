# CHƯƠNG 8: QUẢN LÝ MÃ NGUỒN GIT VÀ QUY TRÌNH LÀM VIỆC NHÓM

## 8.1. Mô hình quản lý tiến độ Agile/Scrum trên Trello
Nhóm 5 thành viên áp dụng quy trình phát triển lặp ngắn theo từng Sprint (5 Sprint xuyên suốt 3 tuần thực hiện đồ án), quản lý trực quan toàn bộ đầu việc trên bảng **Trello**:
- **Cấu trúc cột công việc:** `Backlog` $\rightarrow$ `Sprint hiện tại (To do)` $\rightarrow$ `Đang làm (Doing)` $\rightarrow$ `Review / Kiểm thử` $\rightarrow$ `Hoàn thành (Done)`.
- **Quy ước đặt tên thẻ:**
  - Thẻ tính năng: `[HT-xx] Tên chức năng` (ví dụ: `[HT-04] Ghi danh khóa học và tính tiến độ`).
  - Thẻ kiểm thử: `[TEST] Kiểm thử chéo`, `[TEST] Thực thi 31 Test Cases`.
  - Thẻ sửa lỗi: `[BUG] #<số-issue> - Mô tả ngắn gọn lỗi`.
  - Thẻ tài liệu: `[DOC] Báo cáo`, `[DOC] Đặc tả & CSDL`.
- **Kiểm soát đóng băng chức năng:** Từ Ngày 11, nhóm thực hiện đóng băng tính năng (Feature Freeze), không phát sinh thêm chức năng mới; chỉ duy trì thẻ `[BUG]` và `[DOC]`, đưa cột Lỗi về `0` trong Ngày 12.

## 8.2. Chiến lược phân nhánh Git (Git Branching Strategy)
Mã nguồn dự án được quản lý tập trung trên **GitHub** theo mô hình phân nhánh chuẩn hóa để tránh xung đột mã nguồn và đảm bảo nhánh chính luôn ổn định:
- **`main`:** Lưu trữ phiên bản ổn định nhất, chỉ merge từ `develop` sau khi đã vượt qua kiểm thử và chốt các mốc đánh giá (Mốc 1, Mốc 2, Mốc 3).
- **`develop`:** Nhánh tích hợp chung toàn bộ mã nguồn của nhóm trong quá trình phát triển.
- **`feature/ht-xx-...`:** Nhánh phát triển từng chức năng riêng biệt theo phân công (`TV1` đến `TV5`), tách từ `develop` và merge ngược lại qua Pull Request.
- **`fix/...`:** Nhánh sửa lỗi phát sinh từ kiểm thử nội bộ hoặc kiểm thử chéo (ví dụ: `fix/ht03-duplicate-toast`).
- **`docs/...`:** Nhánh biên soạn tài liệu đặc tả, kịch bản kiểm thử và các chương báo cáo (ví dụ: `docs/bao-cao-c7-c8`).

## 8.3. Quy tắc Commit, Pull Request và Code Review
1. **Quy ước thông điệp Commit (Conventional Commits):**
   - `feat:` thêm chức năng mới (ví dụ: `feat: hoan thanh chuc nang ghi danh va tinh tien do HT-04`).
   - `fix:` sửa lỗi và tự động đóng Issue trên GitHub (ví dụ: `fix: sua loi hien thi trung thong bao dang ky, Closes #12`).
   - `docs:` cập nhật tài liệu, test cases, báo cáo (ví dụ: `docs: viet ban nhap chuong 7 va chuong 8`).
   - `test:` bổ sung hoặc cập nhật kịch bản kiểm thử.
2. **Quy trình Pull Request (PR) & Review:**
   - Thành viên không push trực tiếp vào `main` và `develop`.
   - Mọi thay đổi đều tạo Pull Request vào `develop`, được `TV5` (Git/QA) và `TV1` (Trưởng nhóm) kiểm tra xung đột (`conflict`), đối chiếu chuẩn code và xác nhận kiểm thử trước khi bấm **Merge**.

## 8.4. Thống kê đóng góp Git và GitHub của nhóm
*(Ghi chú: Bạn chạy lệnh `git shortlog -sn --all` trong Terminal của VS Code để điền chính xác số lượng commit thực tế của từng thành viên vào bảng dưới đây)*:

| Thành viên | Vai trò phụ trách | Mã chức năng & Tài liệu | Số lượng Commit | Số Pull Request | Trạng thái |
| :--- | :--- | :--- | :---: | :---: | :---: |
| **TV1** | Trưởng nhóm (PM / Scrum Master) | `HT-07`, `HT-14`, `HT-15`; Chương 1, 2, 9, 10 | *(Điền số)* | *(Điền số)* | Hoàn thành |
| **TV2** | Backend Lead | `HT-03`, `HT-06`; Chương 5 (Kiến trúc), Chương 6 | *(Điền số)* | *(Điền số)* | Hoàn thành |
| **TV3** | Frontend Lead | `HT-01`, `HT-02`, `HT-05`; Chương 5 (UI), Video | *(Điền số)* | *(Điền số)* | Hoàn thành |
| **TV4** | Cơ sở dữ liệu (Database) | `HT-09`, `HT-10`; Chương 3, Chương 5 (CSDL) | *(Điền số)* | *(Điền số)* | Hoàn thành |
| **TV5** | Git & Kiểm thử (QA) | `HT-04`, `HT-11`; Chương 7 (Kiểm thử), Chương 8 (Git) | *(Điền số)* | *(Điền số)* | Hoàn thành |

## 8.5. Nhật ký sử dụng AI và Phản tư (AI Log & Reflection)
Toàn bộ thành viên đều ghi nhận đầy đủ quá trình tương tác với công cụ AI trong thư mục `ai-logs/` (mỗi thành viên $\ge 6$ mục ghi chép kèm prompt, kết quả và đánh giá kiểm chứng):
- **2 trường hợp AI hỗ trợ hiệu quả:**
  1. *Sinh bộ kịch bản kiểm thử biên và phân quyền (`docs/test-cases.md`):* AI giúp liệt kê nhanh các tình huống kiểm thử bảo mật như sửa chéo khóa học của giảng viên khác (`HT-NFR3`), tải tệp vượt quá `5MB` (`HT-NFR4`) và kiểm tra ẩn đáp án trắc nghiệm dưới mã nguồn HTML (`HT-NFR1`).
  2. *Chuẩn hóa mô tả lỗi (GitHub Issues) và cấu trúc báo cáo Markdown:* AI hỗ trợ diễn đạt rõ ràng các bước tái hiện lỗi, kết quả thực tế/mong đợi khi kiểm thử chéo và định dạng bảng biểu thống nhất.
- **2 trường hợp AI xử lý chưa chính xác cần tự kiểm chứng và điều chỉnh:**
  1. *Đoán sai đường dẫn URL hoặc dữ liệu mẫu khi chưa có ngữ cảnh CSDL:* Khi kiểm thử đường dẫn `/teacher/courses/3/edit` trả về `404 Not Found`, AI ban đầu đưa ra hai giả định (chưa có bản ghi ID 3 hoặc bị chặn quyền), đòi hỏi người kiểm thử phải đối chiếu trực tiếp trong danh sách khóa học thực tế và `DatabaseSeeder.php` để xác định nguyên nhân gốc.
  2. *Sinh mã gợi ý validate hoặc cấu trúc bảng báo cáo chưa khớp hoàn toàn với quy ước riêng của nhóm:* Một số tên cột bảng thống kê hoặc tên nhánh Git AI gợi ý ban đầu cần được điều chỉnh lại cho khớp tuyệt đối với tệp hướng dẫn đồ án và file khung `Nhom_4_QLHT_BaoCao...`.