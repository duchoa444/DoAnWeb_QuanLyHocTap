# ĐỀ CƯƠNG ĐỒ ÁN LẬP TRÌNH WEB – NHÓM VHHHN

## 1\. Thông tin chung

| Mục | Nội dung |
| :---- | :---- |
| **Đề tài** | Quản lý học tập (Đề tài E – Nền tảng khóa học trực tuyến miễn phí) |
| **Tên sản phẩm** | Hệ thống quản lý học tập trực tuyến VHHHN-LMS |
| **Lớp, nhóm** | CD CNTT 24WebD – Nhóm VHHHN |
| **Repository** | [https\://github.com/duchoa444/DoAnWeb.git](https://github.com/duchoa444/DoAnWeb.git) (Private, đã mời GV; cấu trúc nhánh `main`, `develop`, `feature/*`, `fix/*`; có sẵn `README.md`, `.gitignore`, thư mục `ai-logs/`) |
| **Board Trello** | [https\://trello.com/invite/b/6af11d2c137d9527eed20231/ATTI8fbcedc7c99e27acb414e1e73343168788B7DD0E/nhomvhhhnquan-ly-hoc-tap](https://trello.com/invite/b/6af11d2c137d9527eed20231/ATTI8fbcedc7c99e27acb414e1e73343168788B7DD0E/nhomvhhhnquan-ly-hoc-tap) (Đã mời GV, thiết lập đủ 7 cột theo quy trình) |
| **Thời gian thực hiện** | 3 tuần (6 buổi học trên lớp vào Thứ 2 & Thứ 6, khung giờ 8:00 – 11:30, kết hợp triển khai ở nhà) |
| **Ngày lập đề cương** | 11/09/2026 |

---

## 2\. Thành viên và vai trò

Mỗi thành viên đảm nhận một vai trò chuyên môn chung trong nhóm và chịu trách nhiệm triển khai **trọn vẹn từ giao diện (Frontend) đến cơ sở dữ liệu (Backend/Database)** cho 2–3 chức năng bắt buộc được phân công:

| Mã | Họ tên | MSSV | Vai trò | Email | Nhiệm vụ chuyên môn & Chức năng phụ trách |
| :---- | :---- | :---- | :---- | :---- | :---- |
| **TV1** | Nguyễn Hoàng Anh Vũ | 0306241254 | **LE** – Leader (Trưởng nhóm) | [0306241254@caothang.edu.vn](mailto:0306241254@caothang.edu.vn) | Quản lý tiến độ, điều phối Trello, lập biên bản họp/stand-up/retrospective, tổng hợp báo cáo & slide.<br>**Chức năng:** `HT-14`, `HT-15`, `HT-07` |
| **TV2** | Phan Văn Hiếu | 0306241442 | **BE 1** – Backend 1 | [0306241442@caothang.edu.vn](mailto:0306241442@caothang.edu.vn) | Thiết kế khung API, xác thực, phân quyền theo vai trò, xử lý logic thi trắc nghiệm và chấm điểm server.<br>**Chức năng:** `HT-03`, `HT-06` |
| **TV3** | Trần Thanh Hồ | 0306241279 | **FE** – Frontend | [0306241279@caothang.edu.vn](mailto:0306241279@caothang.edu.vn) | Thiết kế wireframe, xây dựng giao diện chuẩn responsive (360px & 1366px), trải nghiệm học tập.<br>**Chức năng:** `HT-01`, `HT-02`, `HT-05` |
| **TV4** | Phan Văn Hòa | 0306241444 | **BE 2** – Backend 2 (CSDL) | [0306241444@caothang.edu.vn](mailto:0306241444@caothang.edu.vn) | Thiết kế ERD (7 thực thể), viết migration/script CSDL, tạo dữ liệu mẫu (tối thiểu 10 bản ghi/bảng chính).<br>**Chức năng:** `HT-09`, `HT-10` |
| **TV5** | Nguyễn Thành Nhân | 0306241315 | **GT** – Git & Tester | [0306241315@caothang.edu.vn](mailto:0306241315@caothang.edu.vn) | Quản lý quy tắc Git, bảo vệ nhánh, review PR, tổng hợp test case, kiểm thử bảo mật và kiểm thử chéo.<br>**Chức năng:** `HT-04`, `HT-11` |

---

## 3\. Công nghệ và kiến trúc hệ thống

| Thành phần | Lựa chọn | Lý do chọn & Định hướng áp dụng |
| :---- | :---- | :---- |
| **Backend** | Laravel | Năng lực nhóm đáp ứng tốt, hỗ trợ RESTful API mạnh mẽ, tích hợp sẵn cơ chế bảo mật (băm mật khẩu bcrypt, chống SQL Injection, XSS, CSRF), hỗ trợ phân trang phía server hiệu quả. |
| **Frontend** | ReactJS | Tối ưu trải nghiệm người dùng SPA, linh hoạt tái sử dụng Component, dễ dàng xây dựng giao diện tương thích cả thiết bị di động (360px) và máy tính (1366px). |
| **Cơ sở dữ liệu** | MY SQL | Quản lý dữ liệu có cấu trúc chặt chẽ cho 7 thực thể (`users`, `categories`, `courses`, `lessons`, `enrollments`, `questions`, `quiz_attempts`), hỗ trợ transaction và xóa mềm (`is_deleted`) tốt. |
| **Kiến trúc** | 3 lớp (`Controller – Service – Repository`) | Tách biệt rõ tầng giao tiếp HTTP, tầng xử lý nghiệp vụ và tầng truy xuất dữ liệu; thuận tiện viết tối thiểu 5 unit test cho tầng Service và dễ bảo trì. |
| **Công cụ & Quản lý** | GitHub, Trello, Figma, VS Code, Gemini AI, Chat GPT , Copilot AI | Quản lý mã nguồn theo nhánh nhỏ và Pull Request; theo dõi tiến độ trực quan qua Trello; lưu trữ lịch sử sử dụng AI minh bạch tại thư mục `ai-logs/`. |

---

## 4\. Phạm vi chức năng và yêu cầu kỹ thuật

### 4.1. Chức năng bắt buộc (Mức B – 12 chức năng)

Hệ thống phục vụ 4 phân hệ người dùng: **Khách vãng lai**, **Học viên (`student`)**, **Giảng viên (`teacher`)** và **Quản trị (`admin`)**.

| Mã | Phân hệ | Chức năng | Tiêu chí chấp nhận cốt lõi | Phụ trách |
| :---- | :---- | :---- | :---- | :---- |
| **HT-01** | Khách vãng lai | Danh sách khóa học | Tìm kiếm theo tên khóa học; lọc theo danh mục, giảng viên; phân trang phía server (10–20 bản ghi/trang). | **TV3** |
| **HT-02** | Khách vãng lai | Chi tiết khóa học | Hiển thị mô tả, thông tin giảng viên, danh sách bài học (chưa xem được nội dung chi tiết), tổng số học viên đã ghi danh. | **TV3** |
| **HT-03** | Khách vãng lai | Đăng ký, đăng nhập | Đăng ký chỉ tạo tài khoản Học viên (email duy nhất, mật khẩu tối thiểu 8 ký tự); đăng nhập điều hướng đúng theo vai trò. | **TV2** |
| **HT-04** | Học viên | Ghi danh, khóa học của tôi | Mỗi học viên ghi danh tối đa 1 lần/khóa học; hiển thị danh sách khóa đã ghi danh kèm phần trăm (%) tiến độ hoàn thành. | **TV5** |
| **HT-05** | Học viên | Học bài | Chỉ học viên đã ghi danh mới xem được nội dung văn bản, video nhúng (YouTube), tệp PDF; đánh dấu hoàn thành bài học; chuyển bài trước/sau. | **TV3** |
| **HT-06** | Học viên | Làm bài trắc nghiệm | Câu hỏi 4 lựa chọn (1 đáp án đúng); giới hạn thời gian & chấm điểm tự động tại server; làm tối đa 3 lần, hệ thống lấy điểm cao nhất. | **TV2** |
| **HT-07** | Học viên | Hồ sơ và kết quả học tập | Cập nhật thông tin cá nhân, đổi mật khẩu (có xác nhận mật khẩu cũ); xem lịch sử các lần làm bài trắc nghiệm và điểm số. | **TV1** |
| **HT-09** | Giảng viên | Quản lý khóa học | Thêm, xem, sửa, xóa mềm khóa học của chính mình (tiêu đề, mô tả, ảnh bìa, trạng thái nháp/công khai). | **TV4** |
| **HT-10** | Giảng viên | Quản lý bài học | Thêm, sửa, xóa, sắp xếp thứ tự bài học trong khóa; tải lên tệp PDF (tối đa 5 MB), dán đường dẫn video nhúng (YouTube). | **TV4** |
| **HT-11** | Giảng viên | Quản lý câu hỏi trắc nghiệm | Thêm, sửa, xóa câu hỏi trắc nghiệm cho bài kiểm tra của khóa học; cấu hình thời gian làm bài. | **TV5** |
| **HT-14** | Quản trị | Quản lý người dùng | Cấp/tạo tài khoản Giảng viên; tìm kiếm người dùng; khóa hoặc mở khóa tài khoản (tài khoản bị khóa không thể đăng nhập). | **TV1** |
| **HT-15** | Quản trị | Danh mục và duyệt khóa học | Thêm, sửa, xóa danh mục (không xóa danh mục đang có khóa học); duyệt hoặc ẩn các khóa học vi phạm. | **TV1** |
| **HT-16** | Quản trị | Thống kê | Số khóa học, học viên, lượt làm bài, điểm trung bình theo khóa (biểu đồ). | **TV1** |

### 4.2. Chức năng cộng điểm (Mức C – Tối đa 2 chức năng)

Chỉ triển khai sau **Mốc 2 (Buổi 4\)** khi nhóm đã hoàn thiện toàn bộ 12 chức năng Mức B:

* **HT-08 – Hỏi đáp trong bài học (Phân hệ Học viên/Giảng viên):** Cho phép học viên đặt câu hỏi bình luận bên dưới từng bài học và giảng viên phản hồi trực tiếp.  
* **HT-12 – Theo dõi học viên (Giảng viên):** Xem danh sách học viên đã ghi danh vào khóa học của mình kèm % tiến độ hoàn thành và điểm bài kiểm tra; hỗ trợ xuất danh sách ra tệp CSV.

### 4.3. Yêu cầu phi chức năng trọng tâm

* **Bảo mật & Phân quyền (`NFR-01`, `NFR-02`, `HT-NFR2`, `HT-NFR3`):** Băm mật khẩu bằng `bcrypt`; phòng chống SQL Injection, XSS, CSRF; tuyệt đối không đẩy thông tin nhạy cảm (`.env`, mật khẩu CSDL, API key) lên GitHub. Kiểm tra quyền truy cập tại server (chỉ học viên đã ghi danh mới xem được nội dung bài học; giảng viên chỉ thao tác được trên khóa học của mình).  
* **Bảo mật thi trắc nghiệm (`HT-NFR1`):** Không gửi đáp án đúng xuống trình duyệt trước khi nộp bài; lưu thời gian bắt đầu trên server, từ chối các bài nộp trễ hạn và thực hiện chấm điểm hoàn toàn ở phía backend.  
* **Hiệu năng & Dữ liệu (`NFR-03`, `NFR-05`, `HT-NFR4`):** Phân trang phía server (10–20 bản ghi/trang), thời gian phản hồi dưới 2 giây với dữ liệu mẫu; kiểm tra tính hợp lệ dữ liệu phía server; áp dụng xóa mềm (`is_deleted`) với dữ liệu đã phát sinh giao dịch; không lưu tệp video trực tiếp lên server (chỉ nhúng link).  
* **Giao diện & Bảo trì (`NFR-04`, `NFR-06`):** Giao diện responsive chuẩn trên màn hình **360px** và **1366px**, thông báo lỗi bằng tiếng Việt rõ ràng theo từng trường nhập liệu. Có tệp `README.md` hướng dẫn cài đặt chạy được trên máy sạch trong vòng **15 phút** và có tối thiểu **5 unit test** chạy xanh cho tầng Service.

### 4.4. Ngoài phạm vi (Không thực hiện)

* Thương mại hóa khóa học, tích hợp cổng thanh toán trực tuyến (MoMo, VNPay, Banking API).  
* Lớp học trực tuyến (livestream), thông báo thời gian thực (WebSocket), gửi email tự động.  
* Cấp chứng chỉ hoàn thành khóa học, đa ngôn ngữ.  
* Câu hỏi tự luận chấm tay, hệ thống chấm bài lập trình tự động.  
* Lưu trữ tệp video trực tiếp trên server; các tính năng giám sát chống gian lận nâng cao (webcam, khóa trình duyệt) và ứng dụng di động (Mobile App).

---

## 5\. Kế hoạch Sprint và lộ trình 6 buổi học

### 5.1. Tổng quan 5 Sprint và các mốc kiểm tra

| Sprint | Thời gian | Buổi học & Mốc đánh giá | Tag Git | Sản phẩm bàn giao chính |
| :---- | :---- | :---- | :---- | :---- |
| **Sprint 1** | Buổi 1 đến Buổi 2 (Tuần 1\) | **B1:** Khởi động (Phiếu 1\)<br>**B2:** **Mốc 1 – Thiết kế** (Tính điểm – Phiếu 2\) | `v0.1` | Đề cương được duyệt; Trello 7 cột; ERD (7 bảng), use case, wireframe; khung dự án 3 lớp; dữ liệu mẫu; hoàn thành `HT-03` (Đăng ký/Đăng nhập/Phân quyền). |
| **Sprint 2** | Buổi 2 đến Buổi 3 (Cuối Tuần 1 – Đầu Tuần 2\) | **B3:** Kiểm tra tiến độ & Chuyên đề bảo mật (Phiếu 3\) | — | Hoàn thành nhóm chức năng danh mục, hiển thị và CRUD cơ bản (`HT-01`, `HT-02`, `HT-09`, `HT-10`, `HT-14`, `HT-15`); rà soát và tạo thẻ `[BUG]` bảo mật. |
| **Sprint 3** | Buổi 3 đến Buổi 4 (Tuần 2\) | **B4:** **Mốc 2 – Demo 70% chức năng** (Tính điểm – Phiếu 4\) & Đánh giá chéo lần 1 | `v0.5` | Hoàn tất các luồng nghiệp vụ chính end-to-end (`HT-04`, `HT-05`, `HT-06`, `HT-07`, `HT-11`); sửa lỗi bảo mật; demo đạt tối thiểu 70% khối lượng; viết unit test Service & bảng test case. |
| **Sprint 4** | Buổi 4 đến Buổi 5 (Cuối Tuần 2 – Đầu Tuần 3\) | **B5:** **Đóng băng chức năng** (sau Tiết 1\) & Kiểm thử chéo (Phiếu 5\) | — | Hoàn thiện 100% 12 chức năng Mức B; tối ưu responsive (360px & 1366px); làm chức năng Mức C (`HT-08`, `HT-16`); đạt tối thiểu 5 unit test chạy xanh. |
| **Sprint 5** | Buổi 5 đến Buổi 6 (Tuần 3\) | **Hạn nộp cuối:** 23:59 Thứ 5, Tuần 3<br>**B6:** **Bảo vệ đồ án** (Phiếu 6 & Chấm cuối kỳ) | `v1.0` | Xử lý xong toàn bộ GitHub Issues từ kiểm thử chéo; hoàn thiện `README.md`, báo cáo PDF (10 chương), video demo (3–5 phút), slide (10 trang); nộp đánh giá chéo lần 2 và bảo vệ. |

### 5.2. Kế hoạch chi tiết từng buổi học (Trên lớp và Ở nhà)

* **Buổi 1 (Thứ 2, Tuần 1\) – Khởi động dự án:**  
  * *Trên lớp:* Bầu trưởng nhóm, phân công vai trò; thống nhất phạm vi & công nghệ (ghi AI log đầu tiên); khởi tạo Trello (7 cột, mời GV, mỗi người tạo tối thiểu 3 user story); tạo repo Private (`main`, `develop`, bảo vệ nhánh, `README.md`, `.gitignore`, `ai-logs/`); mỗi thành viên mở và merge PR đầu tiên; trình GV duyệt đề cương và lập biên bản họp số 01\.  
  * *Ở nhà (Thứ 3 – Thứ 5, Tuần 1):* Hoàn thiện ERD 7 bảng, use case, wireframe; viết migration/script CSDL và tạo dữ liệu mẫu (tối thiểu 10 bản ghi/bảng chính); lập trình xong `HT-03` (đăng ký, đăng nhập, phân quyền); chuẩn bị slide/tài liệu trình bày Mốc 1\.  
* **Buổi 2 (Thứ 6, Tuần 1\) – Mốc 1: Thiết kế (Tính điểm):**  
  * *Trên lớp:* Trình bày Mốc 1 trong 10 phút (backlog, ERD, kiến trúc 3 lớp, wireframe, cấu trúc thư mục, Git, AI log); chuyển góp ý của GV thành thẻ Trello và cập nhật thiết kế; tạo khung giao diện cho các chức năng; review Sprint S1, lập Sprint S2, merge `develop` vào `main` và gắn tag `v0.1`.  
  * *Ở nhà (Thứ 7 – Chủ nhật, Tuần 1):* Triển khai CRUD phía Quản trị & Giảng viên (`HT-09`, `HT-10`, `HT-14`, `HT-15`), trang danh sách & chi tiết khóa học (`HT-01`, `HT-02`); làm việc theo nhánh `feature/HT-xx` và review PR chéo.  
* **Buổi 3 (Thứ 2, Tuần 2\) – Kiểm tra tiến độ & Rà soát bảo mật:**  
  * *Trên lớp:* Họp Stand-up (2 phút/người), lập biên bản và lập Sprint S3; tiếp tục lập trình tìm kiếm, lọc, phân trang phía server; GV kiểm tra Git và `ai-logs/`; tham gia chuyên đề kiểm chứng mã AI sinh & bảo mật (SQL Injection, XSS, CSRF), tự rà soát mã nguồn và tạo thẻ `[BUG]`.  
  * *Ở nhà (Thứ 3 – Thứ 5, Tuần 2):* Hoàn thiện các luồng nghiệp vụ chính (`HT-04` Ghi danh, `HT-05` Học bài, `HT-06` Thi trắc nghiệm, `HT-07` Hồ sơ & kết quả, `HT-11` Ngân hàng câu hỏi); kiểm tra dữ liệu phía server, xử lý upload PDF; sửa toàn bộ lỗi bảo mật `[BUG]`; chuẩn bị kịch bản demo Mốc 2\.  
* **Buổi 4 (Thứ 6, Tuần 2\) – Mốc 2: Demo 70% chức năng (Tính điểm):**  
  * *Trên lớp:* Demo tối thiểu 70% chức năng cho GV (kèm kiểm tra commit cá nhân và `ai-logs/`); sửa lỗi theo góp ý; mỗi thành viên viết unit test cho tầng Service của mình và tổng hợp bảng test case; thực hiện đánh giá chéo lần 1 (phiếu giấy), họp Retrospective, lập Sprint S4 và gắn tag `v0.5`.  
  * *Ở nhà (Thứ 7 – Chủ nhật, Tuần 2):* Hoàn tất 100% chức năng bắt buộc (Mức B), kiểm thử responsive trên 360px & 1366px; triển khai 2 chức năng cộng điểm (`HT-08`, `HT-16`); đảm bảo tối thiểu 5 unit test chạy xanh.  
* **Buổi 5 (Thứ 2, Tuần 3\) – Đóng băng chức năng & Kiểm thử chéo:**  
  * *Trên lớp:* Stand-up xác nhận hoàn thành tính năng, lập Sprint S5 và **Đóng băng chức năng** ngay sau Tiết 1 (chỉ còn xử lý thẻ `[BUG]` và `[DOC]`); GT chủ trì kiểm thử chéo sản phẩm của nhóm bạn được phân công và tạo tối thiểu 5 GitHub Issues; cả nhóm sửa các lỗi nhóm bạn báo về trên nhánh `fix/`; lập dàn ý báo cáo 10 chương, phân công viết báo cáo, chương phản tư AI và slide.  
  * *Ở nhà (Thứ 3 – Thứ 5, Tuần 3):* Hoàn tất báo cáo PDF (10 chương), quay video demo (3–5 phút), hoàn thiện slide (10 trang) và `README.md` (cài đặt chạy trong 15 phút); tập thuyết trình 8 phút. **Gắn tag `v1.0`, nộp sản phẩm cuối và nộp phiếu đánh giá chéo lần 2 (tính điểm) trước 23:59 Thứ 5, Tuần 3\.**  
* **Buổi 6 (Thứ 6, Tuần 3\) – Bảo vệ đồ án:**  
  * *Trên lớp:* Bảo vệ trước hội đồng/GV (25 phút/nhóm: 8 phút trình bày & demo sản phẩm, 15 phút vấn đáp cá nhân & giải thích mục AI log ngẫu nhiên, 2 phút nhận xét).  
  * *Sau buổi học:* Leader tổng kết và viết Retrospective cuối cùng trên board Trello.

### 5.3. Quy định vận hành hàng ngày (Daily Workflow)

1. **Quản lý công việc (Trello):** Cập nhật trạng thái thẻ trên 7 cột và bình luận tiến độ mỗi ngày (kể cả ngày làm việc ở nhà); dành 40 phút cuối mỗi buổi học (10:50 – 11:30) để đồng bộ Trello, push code và cập nhật AI log.  
2. **Quản lý mã nguồn (GitHub):** Mỗi chức năng/lỗi tách nhánh riêng (`feature/HT-xx`, `fix/issue-xx`) từ `develop`; commit nhỏ gọn theo chuẩn **Conventional Commits** (`feat:`, `fix:`, `docs:`, `test:`, `refactor:`); mở Pull Request có mô tả rõ ràng, gắn link thẻ Trello và phải được GT/thành viên khác review trước khi merge.  
3. **Nhật ký sử dụng AI (`ai-logs/`):** Ghi nhận trung thực prompt, công cụ sử dụng, phần mã/nội dung AI hỗ trợ và cách thành viên kiểm chứng/chỉnh sửa lại ngay sau mỗi lần sử dụng AI.

---

## 6\. Quản lý rủi ro và cách xử lý

| Rủi ro | Mức độ | Cách phòng tránh / Xử lý |
| :---- | :---- | :---- |
| Thành viên chưa quen công nghệ (Laravel, ReactJS, SQL Server) | Cao | Tổ chức pair-programming trong Tuần 1; BE 1 và BE 2 chuẩn hóa khung dự án 3 lớp và cấu hình kết nối mẫu ngay từ Sprint S1. |
| Xung đột mã nguồn Git hoặc lộ thông tin nhạy cảm lên repo | Trung bình | Chia nhánh nhỏ theo từng mã chức năng `HT-xx`, yêu cầu pull `develop` mỗi ngày trước khi code; cấu hình `.gitignore` loại trừ `.env` ngay từ Buổi 1 và kiểm tra kỹ trước mỗi PR. |
| Gian lận thời gian thi hoặc lộ đáp án đúng xuống trình duyệt | Cao | API lấy đề thi tuyệt đối không trả về trường đáp án đúng (`HT-NFR1`); lưu mốc thời gian bắt đầu trên server, tự động từ chối bài nộp quá giờ và chấm điểm hoàn toàn tại backend. |
| Truy cập trái phép bài học hoặc chỉnh sửa khóa học của người khác | Cao | Áp dụng Middleware và kiểm tra quyền sở hữu/trạng thái ghi danh tại tầng Service cho mọi API (`HT-NFR2`, `HT-NFR3`), không chỉ ẩn nút trên giao diện. |
| Mã do AI sinh tiềm ẩn lỗ hổng bảo mật hoặc thành viên không nắm rõ khi vấn đáp | Cao | Bắt buộc kiểm chứng và tự viết lại/chỉnh sửa mã AI sinh theo chuyên đề bảo mật Buổi 3; review PR chéo nghiêm ngặt và ghi chép chi tiết `ai-logs/` để sẵn sàng giải thích khi vấn đáp Buổi 6\. |
| Trễ tiến độ hoặc phát sinh nhiều lỗi sát hạn nộp `v1.0` | Cao | Hoàn thành 100% chức năng Mức B trước khi làm Mức C; tuân thủ nghiêm kỷ luật **Đóng băng chức năng** từ Tiết 1 Buổi 5 để tập trung xử lý bug và hoàn thiện tài liệu trước 23:59 Thứ 5, Tuần 3\. |

---

## 7\. Duyệt đề cương

- **Ý kiến giảng viên:** ................................................................................................................................................  
- **Kết quả:** ☐ Duyệt      ☐ Duyệt có chỉnh sửa      ☐ Làm lại