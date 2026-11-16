# ĐẶC TẢ CHI TIẾT USE CASE – HỆ THỐNG QUẢN LÝ HỌC TẬP TRỰC TUYẾN (VHHHN-LMS)

Tài liệu này mô tả chi tiết kịch bản tương tác, điều kiện ràng buộc và các luồng xử lý ngoại lệ cho 3 Use Case nghiệp vụ cốt lõi của hệ thống **VHHHN-LMS**:
1. **UC-01 (`HT-04`)**: Ghi danh khóa học
2. **UC-02 (`HT-06`)**: Làm bài trắc nghiệm *(bao gồm luồng phụ: Hết giờ, Hết lượt làm bài)*
3. **UC-03 (`HT-10`)**: Quản lý bài học

---

## 1. Đặc tả Use Case: Ghi danh khóa học (`HT-04`)

### 1.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-04` |
| **Tên Use Case** | Ghi danh khóa học |
| **Tác nhân chính** | Học viên (`Student`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép học viên đăng ký tham gia vào một khóa học đang mở trên hệ thống để có quyền truy cập nội dung bài học (`HT-05`) và thực hiện bài kiểm tra trắc nghiệm (`HT-06`). |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Học viên đã đăng nhập vào hệ thống với tài khoản hợp lệ (vai trò `student`, trạng thái hoạt động).<br>2. Khóa học đang ở trạng thái hiển thị/đã duyệt (`published`) và chưa bị xóa mềm (`deleted_at IS NULL`).<br>3. Học viên chưa từng ghi danh vào khóa học này trước đó. |
| **Hậu điều kiện** | • **Thành công**: Hệ thống tạo bản ghi mới trong bảng `enrollments` (`user_id`, `course_id`, `enrolled_at`), khởi tạo tiến độ học tập ở mức $0\%$, cập nhật nút thao tác thành **"Tiếp tục học"** và hiển thị khóa học trong danh mục **"Khóa học của tôi"**.<br>• **Thất bại**: Không có bản ghi nào được thêm vào CSDL; hệ thống giữ nguyên trạng thái và hiển thị thông báo lỗi tương ứng. |

### 1.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Học viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập trang danh sách khóa học (`HT-01`) và chọn mở trang chi tiết một khóa học (`HT-02`). | Tải thông tin chi tiết khóa học, kiểm tra trạng thái ghi danh của học viên hiện tại và hiển thị nút **"Ghi danh ngay"**. |
| **2** | Nhấn nút **"Ghi danh ngay"**. | Hiển thị hộp thoại xác nhận ghi danh gồm các thông tin: Tên khóa học, tên giảng viên phụ trách, số lượng bài học. |
| **3** | Nhấn **"Xác nhận"** trên hộp thoại. | Kiểm tra tính hợp lệ của khóa học (đang mở, chưa bị xóa mềm) và kiểm tra ràng buộc chưa tồn tại cặp `(user_id, course_id)` trong bảng `enrollments`. |
| **4** | — | Lưu bản ghi ghi danh mới vào bảng `enrollments`, ghi nhận thời điểm `enrolled_at`. |
| **5** | — | Hiển thị thông báo *"Ghi danh khóa học thành công"* và chuyển hướng học viên đến giao diện học bài (`HT-05`) hoặc trang **"Khóa học của tôi"**. |

### 1.3. Luồng phụ (Alternative Flows)

* **AF-01: Học viên hủy xác nhận ghi danh**
  * **Điều kiện kích hoạt**: Tại Bước 3 của luồng chính, học viên nhấn nút **"Hủy"** hoặc đóng hộp thoại xác nhận.
  * **Xử lý**: Hệ thống đóng hộp thoại xác nhận, không lưu dữ liệu vào CSDL và giữ nguyên giao diện trang chi tiết khóa học.

* **AF-02: Khách vãng lai nhấn nút ghi danh**
  * **Điều kiện kích hoạt**: Người dùng chưa đăng nhập (`Guest`) nhấn nút **"Ghi danh ngay"** tại trang chi tiết khóa học.
  * **Xử lý**: Hệ thống lưu lại đường dẫn (URL) khóa học hiện tại vào phiên làm việc (`redirect_url`) và chuyển hướng sang trang **Đăng nhập (`HT-03b`)**. Sau khi người dùng đăng nhập thành công bằng tài khoản Học viên, hệ thống tự động điều hướng quay lại trang chi tiết khóa học để thực hiện tiếp Bước 2.

### 1.4. Luồng ngoại lệ (Exception Flows)

* **EF-01: Ghi danh trùng lặp (Đã ghi danh trước đó)**
  * **Điều kiện kích hoạt**: Học viên mở nhiều tab trình duyệt cùng lúc và gửi yêu cầu ghi danh lặp lại cho cùng một khóa học.
  * **Xử lý**: Ràng buộc duy nhất `UNIQUE(user_id, course_id)` trên bảng `enrollments` tại server ngăn chặn việc chèn trùng bản ghi. Hệ thống trả về thông báo *"Bạn đã ghi danh khóa học này rồi"* và cập nhật nút bấm thành **"Tiếp tục học"**.

* **EF-02: Khóa học vừa bị ẩn hoặc ngừng hoạt động**
  * **Điều kiện kích hoạt**: Tại Bước 3, quản trị viên hoặc giảng viên vừa chuyển khóa học sang trạng thái ẩn/xóa mềm ngay trước thời điểm học viên bấm xác nhận.
  * **Xử lý**: Server từ chối yêu cầu ghi danh, hiển thị thông báo *"Khóa học hiện không khả dụng hoặc đã ngừng nhận học viên"* và điều hướng về trang danh sách khóa học (`HT-01`).

---

## 2. Đặc tả Use Case: Làm bài trắc nghiệm (`HT-06`)

### 2.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-06` |
| **Tên Use Case** | Làm bài trắc nghiệm |
| **Tác nhân chính** | Học viên (`Student`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép học viên làm bài kiểm tra trắc nghiệm trong khóa học đã ghi danh. Hệ thống kiểm soát thời gian làm bài và số lượt làm bài tại server (`HT-NFR1`), tự động chấm điểm khi nộp bài hoặc khi hết giờ. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Học viên đã đăng nhập vào hệ thống.<br>2. Học viên đã ghi danh hợp lệ vào khóa học chứa bài trắc nghiệm (`HT-NFR2`).<br>3. Bài trắc nghiệm đang hoạt động và có ít nhất $1$ câu hỏi hợp lệ.<br>4. Học viên vẫn còn lượt làm bài hợp lệ (nếu bài trắc nghiệm có giới hạn số lần làm bài tối đa `max_attempts`). |
| **Hậu điều kiện** | • **Thành công**: Hệ thống lưu bản ghi lượt làm bài vào bảng `quiz_attempts` (`user_id`, `quiz_id`, `score`, `answers`, `started_at`, `submitted_at`), tính điểm tự động trên thang điểm $10$ và hiển thị kết quả chi tiết.<br>• **Thất bại / Gián đoạn**: Nếu chưa nộp bài và chưa hết giờ, lượt thi đang dở giữ nguyên mốc `started_at` tại server để tiếp tục đếm ngược. |

### 2.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Học viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Trong giao diện học bài (`HT-05`), chọn chức năng **"Làm bài trắc nghiệm"**. | Kiểm tra quyền ghi danh (`HT-NFR2`), truy vấn thông tin bài trắc nghiệm (tên bài, thời gian làm bài $T$ phút, tổng số câu hỏi, số lượt đã làm / tối đa) và hiển thị màn hình hướng dẫn trước khi thi. |
| **2** | Nhấn nút **"Bắt đầu làm bài"**. | Kiểm tra số lượt làm bài hiện tại của học viên. Nếu hợp lệ, khởi tạo bản ghi lượt thi mới ở trạng thái `in_progress`, ghi nhận thời điểm bắt đầu `started_at` và hạn chót `expires_at = started_at + T` tại server. |
| **3** | — | Tải danh sách câu hỏi và các phương án lựa chọn (loại bỏ thông tin đáp án đúng khỏi dữ liệu gửi về client) và khởi chạy đồng hồ đếm ngược. |
| **4** | Lần lượt đọc câu hỏi, chọn đáp án cho từng câu và theo dõi trạng thái các câu đã trả lời. | Ghi nhận các lựa chọn của học viên (tự động lưu tạm định kỳ hoặc lưu trên phiên làm việc để tránh mất dữ liệu). |
| **5** | Nhấn nút **"Nộp bài"** sau khi hoàn thành. | Hiển thị hộp thoại xác nhận nộp bài (cảnh báo nếu còn câu hỏi chưa chọn đáp án). |
| **6** | Nhấn **"Xác nhận nộp bài"**. | Kiểm tra thời điểm nộp bài tại server (`submitted_at <= expires_at`), đối chiếu danh sách câu trả lời với đáp án đúng trong bảng `questions` và tính điểm tự động. |
| **7** | — | Lưu điểm số (`score`), chi tiết bài làm (`answers` dạng JSON) và thời điểm nộp (`submitted_at`) vào bảng `quiz_attempts`. Hiển thị kết quả điểm số, số câu đúng/sai và cập nhật vào lịch sử học tập (`HT-07`). |

### 2.3. Luồng phụ (Alternative Flows)

* **AF-01: Hết lượt làm bài (Out of Attempts)**
  * **Điều kiện kích hoạt**: Tại Bước 1 hoặc Bước 2, hệ thống kiểm tra tổng số lượt học viên đã thực hiện bài trắc nghiệm này trong bảng `quiz_attempts` đã đạt mức tối đa cho phép ($\text{Số lượt đã làm} \ge \text{Số lượt tối đa}$).
  * **Xử lý**:
    1. Hệ thống vô hiệu hóa (disable) nút **"Bắt đầu làm bài"**.
    2. Hiển thị thông báo rõ ràng trên màn hình: *"Bạn đã sử dụng hết số lượt làm bài cho phép đối với bài trắc nghiệm này"*.
    3. Hiển thị danh sách lịch sử điểm các lần làm bài trước đó kèm nút **"Xem chi tiết kết quả"** (`HT-07`).

* **AF-02: Hết giờ làm bài (Time Out)**
  * **Điều kiện kích hoạt**: Trong quá trình làm bài (Bước 4), đồng hồ đếm ngược về `00:00` hoặc thời gian thực trên server chạm mốc `expires_at`.
  * **Xử lý**:
    1. Giao diện lập tức khóa toàn bộ các ô chọn đáp án, không cho phép học viên thay đổi lựa chọn.
    2. Hiển thị thông báo: *"Đã hết thời gian làm bài! Hệ thống đang tự động thu bài và chấm điểm"*.
    3. Client tự động gửi toàn bộ đáp án học viên đã chọn trước đó lên server (trong trường hợp mất kết nối, server tự động chốt dữ liệu đã lưu tạm gần nhất).
    4. Server chấm điểm các câu đã trả lời (những câu chưa chọn tính $0$ điểm), lưu kết quả vào `quiz_attempts` với thời điểm `submitted_at = expires_at` và chuyển thẳng sang màn hình hiển thị điểm số.

* **AF-03: Tải lại trang hoặc mất kết nối tạm thời khi vẫn còn thời gian**
  * **Điều kiện kích hoạt**: Học viên vô tình tải lại trang (`F5`), đóng tab trình duyệt hoặc rớt mạng trong lúc làm bài rồi truy cập lại khi vẫn còn trong thời hạn ($\text{Thời gian hiện tại} < \text{expires\_at}$).
  * **Xử lý**: Hệ thống phát hiện lượt làm bài đang ở trạng thái `in_progress`, khôi phục lại giao diện bài thi cùng các đáp án đã lưu tạm và tiếp tục đếm ngược dựa trên quỹ thời gian còn lại thực tế tại server ($\text{Thời gian còn lại} = \text{expires\_at} - \text{Thời gian hiện tại}$), đảm bảo học viên không bị mất oan lượt thi.

### 2.4. Luồng ngoại lệ (Exception Flows)

* **EF-01: Can thiệp đồng hồ client để nộp bài quá hạn (`HT-NFR1`)**
  * **Điều kiện kích hoạt**: Học viên chỉnh lùi giờ hệ thống trên máy tính cá nhân nhằm kéo dài thời gian làm bài và gửi yêu cầu nộp bài sau mốc `expires_at` của server.
  * **Xử lý**: Server luôn lấy mốc thời gian chuẩn của máy chủ để đối chiếu. Hệ thống từ chối các đáp án gửi lên sau thời hạn hợp lệ (cộng thêm sai số trễ mạng tối đa $3$–$5$ giây), chỉ chấm điểm dựa trên dữ liệu hợp lệ trước hạn và thông báo *"Thời gian làm bài hợp lệ đã kết thúc"*.

* **EF-02: Truy cập trái phép bài trắc nghiệm khi chưa ghi danh (`HT-NFR2`)**
  * **Điều kiện kích hoạt**: Người dùng truy cập trực tiếp đường dẫn URL của bài trắc nghiệm thuộc một khóa học mà mình chưa ghi danh.
  * **Xử lý**: Server chặn yêu cầu (`403 Forbidden`), hiển thị thông báo *"Bạn cần ghi danh khóa học để thực hiện bài trắc nghiệm này"* và điều hướng về trang chi tiết khóa học (`HT-02`).

---

## 3. Đặc tả Use Case: Quản lý bài học (`HT-10`)

### 3.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-10` |
| **Tên Use Case** | Quản lý bài học |
| **Tác nhân chính** | Giảng viên (`Teacher`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép giảng viên tạo mới, xem danh sách, chỉnh sửa nội dung (văn bản, nhúng video YouTube, tải lên tài liệu PDF có dung lượng $\le 5\text{ MB}$), sắp xếp thứ tự và xóa mềm các bài học trong khóa học do mình phụ trách. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Giảng viên đã đăng nhập vào hệ thống với tài khoản có vai trò `teacher` và đang hoạt động.<br>2. Giảng viên là chủ sở hữu hợp lệ của khóa học đang thao tác (`course.teacher_id == current_user.id`, `HT-NFR3`). |
| **Hậu điều kiện** | • **Thành công**: Bản ghi bài học được thêm mới, cập nhật hoặc đánh dấu xóa mềm (`deleted_at`) trong bảng `lessons`; tệp PDF hợp lệ (nếu có) được lưu trữ trên server và danh sách bài học được cập nhật.<br>• **Thất bại**: Không có thay đổi nào được ghi vào CSDL hoặc hệ thống lưu trữ tệp; hiển thị thông báo lỗi cụ thể. |

### 3.2. Luồng sự kiện chính (Main Flow – Tạo mới bài học)

| Bước | Tác nhân (Giảng viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập trang **"Quản lý khóa học của tôi"** (`HT-09`) và chọn **"Quản lý bài học"** tại một khóa học. | Kiểm tra quyền sở hữu khóa học (`HT-NFR3`), truy vấn và hiển thị danh sách các bài học chưa bị xóa mềm sắp xếp theo `order_index` kèm nút **"Thêm bài học mới"**. |
| **2** | Nhấn nút **"Thêm bài học mới"**. | Hiển thị biểu mẫu tạo bài học gồm các trường: Tiêu đề bài học (`title`), Nội dung chi tiết (`content`), Đường dẫn video YouTube (`video_url`), Tệp tài liệu PDF (`pdf_file`) và Thứ tự bài học (`order_index`). |
| **3** | Nhập tiêu đề, soạn nội dung văn bản, dán link video YouTube và/hoặc chọn tệp PDF từ máy tính, sau đó nhấn **"Lưu bài học"**. | Kiểm tra tính hợp lệ của dữ liệu đầu vào: tiêu đề không được để trống, đường dẫn video đúng định dạng (nếu có nhập), tệp đính kèm đúng định dạng `.pdf` và dung lượng $\le 5\text{ MB}$ (nếu có tải lên). |
| **4** | — | Lưu tệp PDF vào thư mục lưu trữ của server và sinh đường dẫn `pdf_url`, sau đó ghi bản ghi mới vào bảng `lessons`. |
| **5** | — | Hiển thị thông báo *"Thêm bài học thành công"* và cập nhật lại danh sách bài học của khóa học. |

### 3.3. Luồng phụ (Alternative Flows)

* **AF-01: Chỉnh sửa nội dung bài học**
  * **Điều kiện kích hoạt**: Tại Bước 1, giảng viên nhấn nút **"Sửa"** ở một bài học hiện có.
  * **Xử lý**:
    1. Hệ thống tải toàn bộ dữ liệu hiện tại của bài học (tiêu đề, nội dung, link video, tên tệp PDF hiện tại, thứ tự) lên biểu mẫu chỉnh sửa.
    2. Giảng viên cập nhật thông tin hoặc tải lên tệp PDF mới thay thế tệp cũ, sau đó nhấn **"Cập nhật bài học"**.
    3. Hệ thống kiểm tra hợp lệ như Bước 3 của luồng chính, cập nhật bản ghi trong bảng `lessons` (xóa tệp PDF cũ trên server nếu có tệp mới thay thế) và thông báo *"Cập nhật bài học thành công"*.

* **AF-02: Xóa bài học (Xóa mềm theo `NFR-05`)**
  * **Điều kiện kích hoạt**: Tại Bước 1, giảng viên nhấn nút **"Xóa"** ở một bài học.
  * **Xử lý**:
    1. Hệ thống hiển thị hộp thoại cảnh báo xác nhận xóa bài học.
    2. Giảng viên nhấn **"Xác nhận xóa"**.
    3. Hệ thống thực hiện **xóa mềm** bằng cách cập nhật trường `deleted_at = NOW()` trong bảng `lessons` (không xóa vật lý dữ liệu nhằm bảo toàn lịch sử tiến độ học tập `lesson_progress` và kết quả trắc nghiệm liên quan), sau đó ẩn bài học khỏi danh sách hiển thị.

* **AF-03: Sắp xếp lại thứ tự bài học**
  * **Điều kiện kích hoạt**: Tại Bước 1, giảng viên điều chỉnh chỉ số thứ tự (`order_index`) của các bài học và nhấn **"Lưu thứ tự"**.
  * **Xử lý**: Hệ thống cập nhật lại giá trị `order_index` cho các bản ghi tương ứng trong bảng `lessons` và sắp xếp lại danh sách bài học theo thứ tự mới.

### 3.4. Luồng ngoại lệ (Exception Flows)

* **EF-01: Tệp PDF vượt quá dung lượng $5\text{ MB}$ hoặc sai định dạng**
  * **Điều kiện kích hoạt**: Tại Bước 3, giảng viên chọn tệp không phải định dạng PDF (kiểm tra cả đuôi tệp `.pdf` lẫn MIME type `application/pdf`) hoặc tệp có dung lượng $> 5\text{ MB}$.
  * **Xử lý**: Hệ thống hủy quá trình tải lên, hiển thị thông báo lỗi *"Tệp tài liệu phải có định dạng PDF và dung lượng không vượt quá $5\text{ MB}$"*, giữ nguyên các nội dung văn bản giảng viên đã nhập để chọn lại tệp.

* **EF-02: Vi phạm quyền sở hữu khóa học (`HT-NFR3`)**
  * **Điều kiện kích hoạt**: Giảng viên cố tình gọi API hoặc truy cập đường dẫn thêm/sửa/xóa bài học thuộc khóa học của giảng viên khác (`course.teacher_id != current_user.id`).
  * **Xử lý**: Server từ chối xử lý (`403 Forbidden`), hiển thị thông báo *"Bạn không có quyền quản lý bài học của khóa học này"* và điều hướng về danh sách khóa học của giảng viên (`HT-09`).

* **EF-03: Thiếu thông tin bắt buộc hoặc sai định dạng URL video**
  * **Điều kiện kích hoạt**: Tại Bước 3, giảng viên bỏ trống tiêu đề bài học hoặc nhập đường dẫn video không hợp lệ.
  * **Xử lý**: Hệ thống đánh dấu lỗi ngay tại trường nhập liệu tương ứng, hiển thị thông báo hướng dẫn định dạng chuẩn và yêu cầu giảng viên chỉnh sửa trước khi lưu.

---

## 4. Bảng tổng hợp quy tắc nghiệp vụ & Bảng CSDL liên quan

| Mã quy tắc | Nội dung quy tắc nghiệp vụ | Áp dụng cho Use Case | Bảng CSDL tác động |
| :--- | :--- | :--- | :--- |
| **BR-01** | Một học viên chỉ được ghi danh tối đa $1$ lần vào cùng một khóa học (`UNIQUE(user_id, course_id)`). | `HT-04` (Ghi danh khóa học) | `enrollments`, `courses` |
| **BR-02** | Học viên bắt buộc phải ghi danh khóa học mới được phép truy cập bài học và làm bài trắc nghiệm (`HT-NFR2`). | `HT-04`, `HT-06` | `enrollments`, `quizzes` |
| **BR-03** | Thời gian làm bài (`expires_at`) và chấm điểm trắc nghiệm được tính toán hoàn toàn tại server (`HT-NFR1`); tự động thu bài khi hết giờ. | `HT-06` (Làm bài trắc nghiệm) | `quizzes`, `questions`, `quiz_attempts` |
| **BR-04** | Số lượt làm bài của học viên không được vượt quá giới hạn lượt làm bài (`max_attempts`) do giảng viên cấu hình. | `HT-06` (Làm bài trắc nghiệm) | `quizzes`, `quiz_attempts` |
| **BR-05** | Giảng viên chỉ được thêm, sửa, xóa bài học trong các khóa học do chính mình tạo (`HT-NFR3`). | `HT-10` (Quản lý bài học) | `courses`, `lessons` |
| **BR-06** | Tài liệu đính kèm bài học phải là tệp `.pdf` với dung lượng $\le 5\text{ MB}$; thao tác xóa bài học áp dụng cơ chế xóa mềm (`NFR-05`). | `HT-10` (Quản lý bài học) | `lessons` |