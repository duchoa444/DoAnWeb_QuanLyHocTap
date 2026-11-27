# ĐẶC TẢ CHI TIẾT 14 USE CASE – HỆ THỐNG QUẢN LÝ HỌC TẬP TRỰC TUYẾN (VHHHN-LMS)

Tài liệu này mô tả chi tiết kịch bản tương tác, điều kiện ràng buộc và các luồng xử lý ngoại lệ cho toàn bộ **14 Use Case chức năng** (12 chức năng bắt buộc và 2 chức năng cộng điểm) của hệ thống **VHHHN-LMS**:

1. **UC-01 (`HT-01`)**: Xem danh sách và tìm kiếm khóa học
2. **UC-02 (`HT-02`)**: Xem chi tiết khóa học
3. **UC-03 (`HT-03`)**: Đăng ký và đăng nhập tài khoản
4. **UC-04 (`HT-04`)**: Ghi danh khóa học
5. **UC-05 (`HT-05`)**: Học bài giảng và theo dõi tiến độ
6. **UC-06 (`HT-06`)**: Làm bài trắc nghiệm
7. **UC-07 (`HT-07`)**: Quản lý hồ sơ cá nhân và lịch sử điểm số
8. **UC-08 (`HT-08`)**: Hỏi đáp và thảo luận trong bài học *(Chức năng cộng điểm)*
9. **UC-09 (`HT-09`)**: Quản lý khóa học của giảng viên
10. **UC-10 (`HT-10`)**: Quản lý bài học
11. **UC-11 (`HT-11`)**: Quản lý ngân hàng câu hỏi trắc nghiệm
12. **UC-12 (`HT-12`)**: Thống kê kết quả học tập và xuất báo cáo CSV *(Chức năng cộng điểm)*
13. **UC-13 (`HT-14`)**: Quản lý người dùng và phân quyền hệ thống
14. **UC-14 (`HT-15`)**: Kiểm duyệt khóa học và quản lý danh mục

---

## 1. Đặc tả Use Case: Xem danh sách và tìm kiếm khóa học (`HT-01`)

### 1.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-01` |
| **Tên Use Case** | Xem danh sách và tìm kiếm khóa học |
| **Tác nhân chính** | Khách vãng lai (`Guest`), Học viên (`Student`), Giảng viên (`Teacher`), Quản trị viên (`Admin`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép người dùng duyệt danh sách các khóa học đang công khai trên hệ thống, tìm kiếm theo từ khóa tên khóa học, lọc theo danh mục hoặc giảng viên phụ trách với phân trang $9$ khóa học/trang. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Hệ thống hoạt động bình thường và kết nối cơ sở dữ liệu thành công.<br>2. Tồn tại danh mục và các khóa học ở trạng thái công khai (`published` / `public`). |
| **Hậu điều kiện** | • **Thành công**: Hiển thị danh sách khóa học công khai khớp điều kiện tìm kiếm/lọc kèm thông tin tóm tắt (ảnh/tiêu đề, danh mục, giảng viên, số lượng bài học) và thanh phân trang.<br>• **Thất bại**: Hiển thị thông báo *"Không tìm thấy khóa học phù hợp"* khi không có dữ liệu khớp bộ lọc. |

### 1.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập trang chủ hoặc trang **"Danh sách khóa học"**. | Truy vấn danh sách danh mục, danh sách giảng viên và các khóa học có trạng thái công khai (`published` / `public`), sắp xếp mới nhất và phân trang $9$ khóa học/trang. |
| **2** | Nhập từ khóa vào ô tìm kiếm, chọn bộ lọc **Danh mục** hoặc **Giảng viên**, sau đó nhấn **"Tìm kiếm / Lọc"**. | Tiếp nhận tham số `keyword`, `category_id`, `teacher_id`, thực hiện truy vấn lọc trên bảng `courses` (kèm `withQueryString()` để giữ điều kiện khi chuyển trang). |
| **3** | — | Hiển thị danh sách kết quả tương ứng kèm số lượng bài học của từng khóa học. |

### 1.3. Luồng phụ (Alternative Flows)

- **AF-01: Chuyển trang kết quả phân trang**
  - **Điều kiện kích hoạt**: Người dùng nhấn vào số trang (Trang 2, Trang 3...) ở thanh phân trang cuối danh sách.
  - **Xử lý**: Hệ thống tải các bản ghi của trang được chọn mà vẫn giữ nguyên các tham số tìm kiếm/lọc đang áp dụng trên URL.
- **AF-02: Đặt lại bộ lọc (Reset)**
  - **Điều kiện kích hoạt**: Người dùng nhấn nút **"Xóa bộ lọc"** hoặc truy cập lại đường dẫn gốc `/courses`.
  - **Xử lý**: Hệ thống xóa toàn bộ tham số lọc và hiển thị lại toàn bộ danh sách khóa học công khai mặc định.

### 1.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Không tìm thấy khóa học khớp từ khóa**
  - **Điều kiện kích hoạt**: Tại Bước 2, từ khóa hoặc tổ hợp bộ lọc không trả về bản ghi nào.
  - **Xử lý**: Hệ thống hiển thị thông báo *"Không tìm thấy khóa học nào phù hợp với điều kiện tìm kiếm"* kèm gợi ý quay lại danh sách đầy đủ.

---

## 2. Đặc tả Use Case: Xem chi tiết khóa học (`HT-02`)

### 2.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-02` |
| **Tên Use Case** | Xem chi tiết khóa học |
| **Tác nhân chính** | Khách vãng lai (`Guest`), Học viên (`Student`), Giảng viên (`Teacher`), Quản trị viên (`Admin`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Hiển thị thông tin chi tiết của một khóa học bao gồm mô tả, giảng viên phụ trách, danh mục, số lượng học viên đã ghi danh, thời lượng bài thi trắc nghiệm và đề cương danh sách các bài học. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Khóa học tồn tại trong CSDL.<br>2. Khóa học ở trạng thái công khai (`published` / `public`), hoặc người đang xem là Quản trị viên (`admin`) hoặc Giảng viên sở hữu khóa học đó. |
| **Hậu điều kiện** | • **Thành công**: Hiển thị trang chi tiết khóa học với nút hành động phù hợp theo vai trò và trạng thái ghi danh hiện tại.<br>• **Thất bại**: Trả về trang lỗi `404 Not Found` nếu khóa học không tồn tại hoặc đang bị ẩn đối với người dùng thường. |

### 2.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Nhấn chọn vào một khóa học từ danh sách (`HT-01`). | Kiểm tra trạng thái khóa học và quyền xem của người dùng hiện tại. |
| **2** | — | Tải thông tin khóa học (`category`, `teacher`), đếm tổng số lượt ghi danh (`enrollments_count`) và lấy danh sách tiêu đề các bài học (`lessons`). |
| **3** | — | Nếu người dùng là Học viên đã đăng nhập, kiểm tra bảng `enrollments` để xác định đã ghi danh hay chưa. |
| **4** | — | Hiển thị giao diện chi tiết khóa học: nếu chưa ghi danh hiển thị nút **"Ghi danh khóa học"**; nếu đã ghi danh hiển thị nút **"Vào học ngay"**. |

### 2.3. Luồng phụ (Alternative Flows)

- **AF-01: Quản trị viên xem khóa học đang ẩn để kiểm duyệt**
  - **Điều kiện kích hoạt**: Tài khoản `admin` truy cập trang chi tiết của một khóa học đang ở trạng thái ẩn/chờ duyệt.
  - **Xử lý**: Hệ thống cho phép hiển thị đầy đủ thông tin khóa học, danh sách bài học và hiển thị thêm nút **"Vào xem nội dung bài học"** để phục vụ công tác kiểm duyệt nội dung (`HT-14`).

### 2.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Truy cập khóa học đang bị ẩn hoặc không tồn tại**
  - **Điều kiện kích hoạt**: Người dùng thường (`Guest`, `Student`) truy cập URL của khóa học có trạng thái ẩn (`hidden` / `draft`) hoặc ID không tồn tại.
  - **Xử lý**: Hệ thống từ chối truy cập và trả về mã lỗi `404 Not Found`.

---

## 3. Đặc tả Use Case: Đăng ký và đăng nhập tài khoản (`HT-03`)

### 3.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-03` |
| **Tên Use Case** | Đăng ký và đăng nhập tài khoản |
| **Tác nhân chính** | Khách vãng lai (`Guest`), Người dùng đã có tài khoản (`Student`, `Teacher`, `Admin`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép khách vãng lai đăng ký tài khoản Học viên mới hoặc đăng nhập vào hệ thống bằng Email và Mật khẩu; khởi tạo phiên làm việc (Session) và điều hướng theo đúng vai trò. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Người dùng chưa đăng nhập vào hệ thống.<br>2. Đối với đăng ký: Email chưa từng tồn tại trong bảng `users`.<br>3. Đối với đăng nhập: Tài khoản tồn tại và ở trạng thái hoạt động (`active`). |
| **Hậu điều kiện** | • **Thành công**: Tạo bản ghi người dùng mới (nếu đăng ký) với mật khẩu băm `Bcrypt` và vai trò mặc định `student`; khởi tạo phiên đăng nhập hợp lệ và chuyển hướng về trang tương ứng.<br>• **Thất bại**: Không tạo phiên đăng nhập; hiển thị thông báo lỗi cụ thể tại biểu mẫu. |

### 3.2. Luồng sự kiện chính (Main Flow – Đăng nhập)

| Bước | Tác nhân | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập trang **"Đăng nhập"**. | Hiển thị biểu mẫu đăng nhập gồm các trường: `Email`, `Mật khẩu` và tùy chọn `Ghi nhớ đăng nhập`. |
| **2** | Nhập Email, Mật khẩu và nhấn **"Đăng nhập"**. | Kiểm tra tính hợp lệ dữ liệu đầu vào (`required`, `email`) và đối chiếu thông tin xác thực trong bảng `users`. |
| **3** | — | Xác thực mật khẩu khớp với mã băm trong CSDL, tạo lại Session ID chống tấn công Session Fixation. |
| **4** | — | Điều hướng người dùng về trang đích theo vai trò: Trang chủ/Khóa học (`student`), Quản lý khóa học (`teacher`), hoặc Bảng điều khiển quản trị (`admin`). |

### 3.3. Luồng phụ (Alternative Flows)

- **AF-01: Đăng ký tài khoản Học viên mới (`HT-03a`)**
  - **Điều kiện kích hoạt**: Khách vãng lai chọn tab/trang **"Đăng ký"**.
  - **Xử lý**:
    1. Hệ thống hiển thị biểu mẫu gồm: `Họ và tên`, `Email`, `Mật khẩu` (tối thiểu $8$ ký tự), `Xác nhận mật khẩu`.
    2. Người dùng điền thông tin và nhấn **"Đăng ký tài khoản"**.
    3. Hệ thống kiểm tra Email duy nhất (`unique:users,email`), băm mật khẩu bằng `Hash::make()`, lưu bản ghi mới với `role = 'student'`, tự động đăng nhập và chuyển về trang danh sách khóa học.
- **AF-02: Đăng xuất khỏi hệ thống**
  - **Điều kiện kích hoạt**: Người dùng đang đăng nhập nhấn nút **"Đăng xuất"** trên thanh điều hướng.
  - **Xử lý**: Hệ thống gửi yêu cầu `POST /logout` kèm `@csrf`, hủy phiên làm việc hiện tại (`invalidate`), tạo mới CSRF token và chuyển hướng về trang chủ.

### 3.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Sai Email hoặc Mật khẩu**
  - **Điều kiện kích hoạt**: Tại Bước 3, Email không tồn tại hoặc mật khẩu không khớp.
  - **Xử lý**: Hệ thống trả về thông báo chung *"Email hoặc mật khẩu không chính xác"* (không tiết lộ cụ thể trường nào sai để đảm bảo bảo mật), giữ lại giá trị Email đã nhập.
- **EF-02: Tài khoản bị khóa bởi Quản trị viên**
  - **Điều kiện kích hoạt**: Người dùng đăng nhập bằng tài khoản có trạng thái bị khóa (`locked` / `inactive`).
  - **Xử lý**: Hệ thống từ chối cấp phiên đăng nhập và hiển thị thông báo *"Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Quản trị viên"*.
- **EF-03: Email đăng ký đã tồn tại hoặc mật khẩu xác nhận không khớp**
  - **Điều kiện kích hoạt**: Tại `AF-01`, Email đã có trong bảng `users` hoặc mật khẩu dưới $8$ ký tự.
  - **Xử lý**: Hiển thị lỗi Validation ngay dưới trường nhập liệu tương ứng và yêu cầu nhập lại.

---

## 4. Đặc tả Use Case: Ghi danh khóa học (`HT-04`)

### 4.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-04` |
| **Tên Use Case** | Ghi danh khóa học |
| **Tác nhân chính** | Học viên (`Student`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép học viên đăng ký tham gia vào một khóa học đang mở trên hệ thống để có quyền truy cập nội dung bài học (`HT-05`) và thực hiện bài kiểm tra trắc nghiệm (`HT-06`). |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Học viên đã đăng nhập vào hệ thống với tài khoản hợp lệ (vai trò `student`, trạng thái hoạt động).<br>2. Khóa học đang ở trạng thái hiển thị/đã duyệt (`published` / `public`) và chưa bị xóa mềm (`deleted_at IS NULL`).<br>3. Học viên chưa từng ghi danh vào khóa học này trước đó. |
| **Hậu điều kiện** | • **Thành công**: Hệ thống tạo bản ghi mới trong bảng `enrollments` (`user_id`, `course_id`, `enrolled_at`), khởi tạo tiến độ học tập ở mức $0\%$, cập nhật nút thao tác thành **"Vào học ngay"** và hiển thị khóa học trong danh mục **"Khóa học của tôi"**.<br>• **Thất bại**: Không có bản ghi nào được thêm vào CSDL; hệ thống giữ nguyên trạng thái và hiển thị thông báo lỗi tương ứng. |

### 4.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Học viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập trang danh sách khóa học (`HT-01`) và chọn mở trang chi tiết một khóa học (`HT-02`). | Tải thông tin chi tiết khóa học, kiểm tra trạng thái ghi danh của học viên hiện tại và hiển thị nút **"Ghi danh khóa học"**. |
| **2** | Nhấn nút **"Ghi danh khóa học"**. | Hiển thị hộp thoại xác nhận ghi danh gồm các thông tin: Tên khóa học, tên giảng viên phụ trách, số lượng bài học. |
| **3** | Nhấn **"Xác nhận"** trên hộp thoại. | Kiểm tra tính hợp lệ của khóa học (đang mở, chưa bị xóa mềm) và kiểm tra ràng buộc chưa tồn tại cặp `(user_id, course_id)` trong bảng `enrollments`. |
| **4** | — | Lưu bản ghi ghi danh mới vào bảng `enrollments`, ghi nhận thời điểm `enrolled_at`. |
| **5** | — | Hiển thị thông báo *"Ghi danh khóa học thành công!"* và chuyển hướng học viên đến trang **"Khóa học của tôi"** hoặc giao diện học bài (`HT-05`). |

### 4.3. Luồng phụ (Alternative Flows)

- **AF-01: Học viên hủy xác nhận ghi danh**
  - **Điều kiện kích hoạt**: Tại Bước 3 của luồng chính, học viên nhấn nút **"Hủy"** hoặc đóng hộp thoại xác nhận.
  - **Xử lý**: Hệ thống đóng hộp thoại xác nhận, không lưu dữ liệu vào CSDL và giữ nguyên giao diện trang chi tiết khóa học.
- **AF-02: Khách vãng lai nhấn nút ghi danh**
  - **Điều kiện kích hoạt**: Người dùng chưa đăng nhập (`Guest`) nhấn nút **"Ghi danh khóa học"** tại trang chi tiết khóa học.
  - **Xử lý**: Middleware `auth` lưu lại đường dẫn khóa học hiện tại vào phiên làm việc và chuyển hướng sang trang **Đăng nhập (`HT-03`)**. Sau khi đăng nhập thành công bằng tài khoản Học viên, hệ thống tự động điều hướng quay lại trang chi tiết khóa học.

### 4.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Ghi danh trùng lặp (Đã ghi danh trước đó)**
  - **Điều kiện kích hoạt**: Học viên mở nhiều tab trình duyệt cùng lúc và gửi yêu cầu ghi danh lặp lại cho cùng một khóa học.
  - **Xử lý**: Hệ thống kiểm tra tồn tại trong `enrollments` (kèm ràng buộc `UNIQUE(user_id, course_id)`), ngăn chặn việc chèn trùng bản ghi và trả về thông báo *"Bạn đã ghi danh khóa học này rồi"*.
- **EF-02: Khóa học vừa bị ẩn hoặc ngừng hoạt động**
  - **Điều kiện kích hoạt**: Tại Bước 3, quản trị viên vừa chuyển khóa học sang trạng thái ẩn ngay trước thời điểm học viên bấm xác nhận.
  - **Xử lý**: Server từ chối yêu cầu ghi danh, hiển thị thông báo *"Khóa học này chưa được công khai"* và giữ nguyên trạng thái.
- **EF-03: Tài khoản Giảng viên hoặc Quản trị viên gửi yêu cầu ghi danh**
  - **Điều kiện kích hoạt**: Người dùng có vai trò `teacher` hoặc `admin` cố tình gọi trực tiếp route `POST /courses/{course}/enroll`.
  - **Xử lý**: Server từ chối yêu cầu với mã lỗi `403 Forbidden` (*"Chỉ tài khoản Học viên mới có thể ghi danh khóa học"*).

---

## 5. Đặc tả Use Case: Học bài giảng và theo dõi tiến độ (`HT-05`)

### 5.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-05` |
| **Tên Use Case** | Học bài giảng và theo dõi tiến độ |
| **Tác nhân chính** | Học viên (`Student`) |
| **Tác nhân phụ** | Giảng viên sở hữu khóa học (`Teacher`), Quản trị viên (`Admin`) *(chế độ xem trước/kiểm duyệt)* |
| **Mô tả tóm tắt** | Cho phép học viên đã ghi danh truy cập giao diện học tập của khóa học, xem nội dung bài giảng (văn bản, video YouTube nhúng, tài liệu PDF đính kèm), điều hướng bài trước/sau và đánh dấu hoàn thành từng bài học để cập nhật tỷ lệ `%` tiến độ. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Học viên đã đăng nhập và có bản ghi ghi danh hợp lệ trong bảng `enrollments` đối với khóa học (`HT-NFR2`).<br>2. Khóa học đang ở trạng thái công khai và có ít nhất $1$ bài học hoạt động. |
| **Hậu điều kiện** | • **Thành công**: Hiển thị nội dung bài học đang chọn; khi đánh dấu hoàn thành, hệ thống ghi nhận vào bảng `lesson_progress` và cập nhật tỷ lệ hoàn thành $\text{Tiến độ (\%)} = \left\lfloor \frac{\text{Số bài đã hoàn thành}}{\text{Tổng số bài học}} \times 100 \right\rfloor$.<br>• **Thất bại**: Chặn truy cập nếu chưa ghi danh hoặc báo lỗi nếu khóa học chưa có bài học. |

### 5.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Học viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Nhấn **"Vào học ngay"** từ trang chi tiết khóa học (`HT-02`) hoặc trang **"Khóa học của tôi"**. | Kiểm tra quyền truy cập ở server (`isEnrolled` hoặc `isOwnerOrAdmin`). Tải danh sách bài học của khóa và chọn bài học đầu tiên (hoặc bài học theo `lessonId`). |
| **2** | Xem nội dung bài giảng (đọc văn bản, phát video YouTube nhúng hoặc mở/tải tài liệu PDF). | Hiển thị nội dung chi tiết bài học hiện tại, thanh mục lục bên cạnh đánh dấu trạng thái hoàn thành của từng bài và các nút điều hướng **Bài trước / Bài sau**. |
| **3** | Nhấn nút **"Đánh dấu hoàn thành bài học"** sau khi học xong. | Kiểm tra quyền ghi danh của học viên, kiểm tra chống trùng lặp trong bảng `lesson_progress` cho cặp `(user_id, lesson_id)`. |
| **4** | — | Lưu bản ghi hoàn thành vào `lesson_progress` (`is_completed = true`, `completed_at = NOW()`), tính lại `%` tiến độ khóa học và hiển thị thông báo *"Đã đánh dấu hoàn thành bài học!"*. |

### 5.3. Luồng phụ (Alternative Flows)

- **AF-01: Chuyển đổi giữa các bài học (Bài trước / Bài sau / Chọn trên mục lục)**
  - **Điều kiện kích hoạt**: Học viên nhấn vào tên một bài học bất kỳ trên danh sách mục lục hoặc nhấn nút **"Bài tiếp theo"** / **"Bài trước"**.
  - **Xử lý**: Hệ thống tải nội dung tương ứng của bài học được chọn và giữ nguyên trạng thái hoàn thành của các bài trước đó.
- **AF-02: Giảng viên phụ trách hoặc Quản trị viên vào xem nội dung bài học**
  - **Điều kiện kích hoạt**: Giảng viên sở hữu khóa học (`course.teacher_id == user.id`) hoặc `admin` truy cập đường dẫn `/courses/{course}/learn`.
  - **Xử lý**: Hệ thống cho phép xem toàn bộ nội dung video/PDF/văn bản để kiểm tra chất lượng giảng dạy, nhưng ẩn/chặn thao tác lưu tiến độ học tập cá nhân (`403 Forbidden` nếu gọi `completeLesson`).

### 5.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Học viên chưa ghi danh cố tình truy cập trang học bài (`HT-NFR2`)**
  - **Điều kiện kích hoạt**: Người dùng chưa có bản ghi trong `enrollments` truy cập trực tiếp URL `/courses/{course}/learn`.
  - **Xử lý**: Server chặn truy cập, chuyển hướng về trang chi tiết khóa học (`courses.show`) kèm thông báo *"Bạn cần ghi danh khóa học trước khi vào học"*.
- **EF-02: Khóa học chưa có bài học nào**
  - **Điều kiện kích hoạt**: Tại Bước 1, danh sách bài học của khóa rỗng (`$lessons->isEmpty()`).
  - **Xử lý**: Hệ thống chuyển hướng về trang chi tiết khóa học và thông báo *"Khóa học này hiện chưa có bài học nào"*.

---

## 6. Đặc tả Use Case: Làm bài trắc nghiệm (`HT-06`)

### 6.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-06` |
| **Tên Use Case** | Làm bài trắc nghiệm |
| **Tác nhân chính** | Học viên (`Student`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép học viên làm bài kiểm tra trắc nghiệm trong khóa học đã ghi danh. Hệ thống kiểm soát thời gian làm bài và số lượt làm bài tại server (`HT-NFR1`), tự động chấm điểm khi nộp bài hoặc khi hết giờ. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Học viên đã đăng nhập vào hệ thống.<br>2. Học viên đã ghi danh hợp lệ vào khóa học chứa bài trắc nghiệm (`HT-NFR2`).<br>3. Khóa học có ít nhất $1$ câu hỏi trắc nghiệm hợp lệ.<br>4. Học viên vẫn còn lượt làm bài hợp lệ (chưa vượt quá số lần tối đa `max_attempts`, mặc định $3$ lượt). |
| **Hậu điều kiện** | • **Thành công**: Hệ thống lưu bản ghi lượt làm bài vào bảng `quiz_attempts` (`user_id`, `course_id`, `score`, `answers`, `started_at`, `submitted_at`), tính điểm tự động trên thang điểm $10$ và hiển thị kết quả chi tiết.<br>• **Thất bại / Gián đoạn**: Nếu chưa nộp bài và chưa hết giờ, lượt thi đang dở giữ nguyên mốc `started_at` tại server để tiếp tục đếm ngược. |

### 6.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Học viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Trong giao diện học bài (`HT-05`), chọn chức năng **"Làm bài trắc nghiệm"**. | Kiểm tra quyền ghi danh (`HT-NFR2`), truy vấn thông tin bài thi của khóa học (thời gian làm bài `quiz_minutes` phút, tổng số câu hỏi, số lượt đã làm / tối đa) và hiển thị màn hình hướng dẫn trước khi thi. |
| **2** | Nhấn nút **"Bắt đầu làm bài"**. | Kiểm tra số lượt làm bài hiện tại của học viên. Nếu hợp lệ, khởi tạo bản ghi lượt thi mới ở trạng thái `in_progress`, ghi nhận thời điểm bắt đầu `started_at` và hạn chót `expires_at = started_at + quiz_minutes` tại server. |
| **3** | — | Tải danh sách câu hỏi và các phương án lựa chọn A, B, C, D (loại bỏ hoàn toàn cột `correct_option` khỏi dữ liệu gửi về client) và khởi chạy đồng hồ đếm ngược. |
| **4** | Lần lượt đọc câu hỏi, chọn đáp án cho từng câu và theo dõi trạng thái các câu đã trả lời. | Ghi nhận các lựa chọn của học viên (tự động lưu tạm định kỳ hoặc lưu trên phiên làm việc để tránh mất dữ liệu). |
| **5** | Nhấn nút **"Nộp bài"** sau khi hoàn thành. | Hiển thị hộp thoại xác nhận nộp bài (cảnh báo nếu còn câu hỏi chưa chọn đáp án). |
| **6** | Nhấn **"Xác nhận nộp bài"**. | Kiểm tra thời điểm nộp bài tại server (`submitted_at <= expires_at`), đối chiếu danh sách câu trả lời với đáp án đúng trong bảng `questions` và tính điểm tự động theo thang điểm $10$. |
| **7** | — | Lưu điểm số (`score`), chi tiết bài làm (`answers` dạng JSON) và thời điểm nộp (`submitted_at`) vào bảng `quiz_attempts`. Hiển thị kết quả điểm số, số câu đúng/sai và cập nhật vào lịch sử học tập (`HT-07`). |

### 6.3. Luồng phụ (Alternative Flows)

- **AF-01: Hết lượt làm bài (Out of Attempts)**
  - **Điều kiện kích hoạt**: Tại Bước 1 hoặc Bước 2, hệ thống kiểm tra tổng số lượt học viên đã thực hiện bài trắc nghiệm này trong bảng `quiz_attempts` đã đạt mức tối đa cho phép ($\text{Số lượt đã làm} \ge \text{Số lượt tối đa}$).
  - **Xử lý**:
    1. Hệ thống vô hiệu hóa (disable) nút **"Bắt đầu làm bài"**.
    2. Hiển thị thông báo rõ ràng trên màn hình: *"Bạn đã sử dụng hết số lượt làm bài cho phép đối với khóa học này"*.
    3. Hiển thị danh sách lịch sử điểm các lần làm bài trước đó kèm mức điểm cao nhất (`HT-07`).
- **AF-02: Hết giờ làm bài (Time Out)**
  - **Điều kiện kích hoạt**: Trong quá trình làm bài (Bước 4), đồng hồ đếm ngược về `00:00` hoặc thời gian thực trên server chạm mốc `expires_at`.
  - **Xử lý**:
    1. Giao diện lập tức khóa toàn bộ các ô chọn đáp án, không cho phép học viên thay đổi lựa chọn.
    2. Hiển thị thông báo: *"Đã hết thời gian làm bài! Hệ thống đang tự động thu bài và chấm điểm"*.
    3. Client tự động gửi toàn bộ đáp án học viên đã chọn trước đó lên server (trong trường hợp mất kết nối, server tự động chốt dữ liệu đã lưu tạm gần nhất).
    4. Server chấm điểm các câu đã trả lời (những câu chưa chọn tính $0$ điểm), lưu kết quả vào `quiz_attempts` với thời điểm `submitted_at = expires_at` và chuyển thẳng sang màn hình hiển thị điểm số.
- **AF-03: Tải lại trang hoặc mất kết nối tạm thời khi vẫn còn thời gian**
  - **Điều kiện kích hoạt**: Học viên vô tình tải lại trang (`F5`), đóng tab trình duyệt hoặc rớt mạng trong lúc làm bài rồi truy cập lại khi vẫn còn trong thời hạn ($\text{Thời gian hiện tại} < \text{expires\_at}$).
  - **Xử lý**: Hệ thống phát hiện lượt làm bài đang ở trạng thái `in_progress`, khôi phục lại giao diện bài thi cùng các đáp án đã lưu tạm và tiếp tục đếm ngược dựa trên quỹ thời gian còn lại thực tế tại server ($\text{Thời gian còn lại} = \text{expires\_at} - \text{Thời gian hiện tại}$).

### 6.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Can thiệp đồng hồ client để nộp bài quá hạn (`HT-NFR1`)**
  - **Điều kiện kích hoạt**: Học viên chỉnh lùi giờ hệ thống trên máy tính cá nhân nhằm kéo dài thời gian làm bài và gửi yêu cầu nộp bài sau mốc `expires_at` của server.
  - **Xử lý**: Server luôn lấy mốc thời gian chuẩn của máy chủ để đối chiếu. Hệ thống từ chối các đáp án gửi lên sau thời hạn hợp lệ (cộng thêm sai số trễ mạng tối đa $3$–$5$ giây), chỉ chấm điểm dựa trên dữ liệu hợp lệ trước hạn và thông báo *"Thời gian làm bài hợp lệ đã kết thúc"*.
- **EF-02: Truy cập trái phép bài trắc nghiệm khi chưa ghi danh (`HT-NFR2`)**
  - **Điều kiện kích hoạt**: Người dùng truy cập trực tiếp đường dẫn URL của bài trắc nghiệm thuộc một khóa học mà mình chưa ghi danh.
  - **Xử lý**: Server chặn yêu cầu (`403 Forbidden`), hiển thị thông báo *"Bạn cần ghi danh khóa học để thực hiện bài trắc nghiệm này"* và điều hướng về trang chi tiết khóa học (`HT-02`).

---

## 7. Đặc tả Use Case: Quản lý hồ sơ cá nhân và lịch sử điểm số (`HT-07`)

### 7.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-07` |
| **Tên Use Case** | Quản lý hồ sơ cá nhân và lịch sử điểm số |
| **Tác nhân chính** | Học viên (`Student`), Người dùng đã đăng nhập (`Teacher`, `Admin`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép học viên xem và cập nhật thông tin cá nhân (Họ tên, Email), đổi mật khẩu an toàn và tra cứu toàn bộ lịch sử các lần làm bài trắc nghiệm kèm điểm số, thời gian nộp bài và trạng thái Đạt / Chưa đạt. |
| **Mức độ ưu tiên** | Trung bình (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Người dùng đã đăng nhập hợp lệ vào hệ thống. |
| **Hậu điều kiện** | • **Thành công**: Thông tin cá nhân hoặc mật khẩu mới (đã băm `Bcrypt`) được cập nhật vào bảng `users`; bảng lịch sử làm bài hiển thị đầy đủ các bản ghi từ `quiz_attempts` của người dùng hiện tại.<br>• **Thất bại**: Giữ nguyên dữ liệu cũ trong CSDL và hiển thị thông báo lỗi tương ứng. |

### 7.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Học viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập trang **"Hồ sơ cá nhân"** trên thanh điều hướng. | Truy vấn thông tin người dùng hiện tại từ `users` và danh sách lịch sử làm bài từ `quiz_attempts` (kèm tên khóa học, thời gian bắt đầu, thời gian nộp, điểm số, phân trang). |
| **2** | Chỉnh sửa **Họ và tên** hoặc **Email đăng nhập** tại khối *"Thông tin cá nhân"*, sau đó nhấn **"Lưu thay đổi"**. | Kiểm tra dữ liệu hợp lệ (`name` bắt buộc, `email` đúng định dạng và duy nhất trừ chính bản ghi hiện tại). |
| **3** | — | Cập nhật bản ghi trong bảng `users` và hiển thị thông báo *"Cập nhật thông tin cá nhân thành công!"*. |
| **4** | Theo dõi bảng **"Lịch sử các lần làm bài & Điểm số"** ở nửa dưới trang. | Hiển thị từng lượt thi với điểm số (`score / 10`) và nhãn đánh giá (**Đạt** nếu $\text{điểm} \ge 5.0$, **Chưa đạt** nếu $\text{điểm} < 5.0$). |

### 7.3. Luồng phụ (Alternative Flows)

- **AF-01: Đổi mật khẩu tài khoản**
  - **Điều kiện kích hoạt**: Tại khối *"Đổi mật khẩu"* trên trang hồ sơ, người dùng nhập `Mật khẩu hiện tại`, `Mật khẩu mới` ($\ge 8$ ký tự), `Xác nhận mật khẩu mới` và nhấn **"Cập nhật mật khẩu"**.
  - **Xử lý**:
    1. Hệ thống đối chiếu `Mật khẩu hiện tại` với mã băm trong CSDL bằng `Hash::check()`.
    2. Nếu khớp, băm mật khẩu mới và lưu vào bảng `users`, sau đó hiển thị thông báo *"Đổi mật khẩu thành công!"*.

### 7.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Nhập sai mật khẩu hiện tại khi đổi mật khẩu**
  - **Điều kiện kích hoạt**: Tại `AF-01`, `Mật khẩu hiện tại` không khớp với mật khẩu đang lưu trong CSDL.
  - **Xử lý**: Hệ thống từ chối cập nhật và hiển thị thông báo lỗi *"Mật khẩu hiện tại không chính xác"* ngay dưới ô nhập liệu.
- **EF-02: Email mới bị trùng với tài khoản khác**
  - **Điều kiện kích hoạt**: Tại Bước 2, Email mới đã được một người dùng khác sử dụng.
  - **Xử lý**: Báo lỗi Validation *"Email này đã tồn tại trên hệ thống"* và giữ nguyên dữ liệu cũ.

---

## 8. Đặc tả Use Case: Hỏi đáp và thảo luận trong bài học (`HT-08` – Cộng điểm)

### 8.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-08` |
| **Tên Use Case** | Hỏi đáp và thảo luận trong bài học *(Chức năng cộng điểm)* |
| **Tác nhân chính** | Học viên (`Student`), Giảng viên phụ trách khóa học (`Teacher`) |
| **Tác nhân phụ** | Quản trị viên (`Admin`) |
| **Mô tả tóm tắt** | Cho phép học viên đặt câu hỏi, bình luận thảo luận ngay bên dưới từng bài học và cho phép giảng viên phụ trách (hoặc học viên khác) phản hồi giải đáp thắc mắc theo cấu trúc cây cha – con. |
| **Mức độ ưu tiên** | Thấp / Mở rộng (Chức năng cộng điểm) |
| **Tiền điều kiện** | 1. Người dùng đã đăng nhập và có quyền truy cập bài học (Học viên đã ghi danh khóa học hoặc Giảng viên sở hữu khóa học).<br>2. Bài học đang tồn tại và chưa bị xóa mềm. |
| **Hậu điều kiện** | • **Thành công**: Bản ghi câu hỏi/phản hồi mới được lưu vào bảng `lesson_comments` (`lesson_id`, `user_id`, `parent_id`, `content`) và hiển thị ngay trong khung thảo luận của bài học.<br>• **Thất bại**: Không lưu vào CSDL và hiển thị thông báo lỗi. |

### 8.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Cuộn xuống khu vực **"Hỏi đáp & Thảo luận"** bên dưới nội dung bài học (`HT-05`). | Truy vấn danh sách các câu hỏi gốc (`parent_id IS NULL`) và các phản hồi con (`replies`) thuộc bài học hiện tại kèm tên và vai trò người gửi. |
| **2** | Nhập nội dung thắc mắc vào ô bình luận và nhấn **"Gửi câu hỏi"**. | Kiểm tra quyền truy cập khóa học và kiểm tra nội dung không được rỗng, lọc mã độc XSS. |
| **3** | — | Lưu bản ghi mới vào bảng `lesson_comments` (`parent_id = NULL`), tải lại khung thảo luận và thông báo *"Đã đăng câu hỏi thành công"*. |

### 8.3. Luồng phụ (Alternative Flows)

- **AF-01: Giảng viên hoặc học viên trả lời một câu hỏi có sẵn (Reply)**
  - **Điều kiện kích hoạt**: Người dùng nhấn nút **"Trả lời"** bên dưới một câu hỏi cụ thể.
  - **Xử lý**:
    1. Hiển thị ô nhập phản hồi ngay bên dưới câu hỏi gốc.
    2. Người dùng nhập nội dung giải đáp và nhấn **"Gửi phản hồi"**.
    3. Hệ thống lưu bản ghi với `parent_id` trỏ tới ID của câu hỏi gốc; nếu người trả lời là Giảng viên phụ trách khóa học, hệ thống gắn nhãn nổi bật **"Giảng viên"**.
- **AF-02: Xóa bình luận của chính mình (hoặc Giảng viên/Admin kiểm duyệt)**
  - **Điều kiện kích hoạt**: Chủ sở hữu bình luận, Giảng viên phụ trách khóa học hoặc Admin nhấn nút **"Xóa"** tại một bình luận.
  - **Xử lý**: Hệ thống xác nhận quyền sở hữu/quản lý, xóa bình luận khỏi khung thảo luận và thông báo thành công.

### 8.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Gửi nội dung trống hoặc vượt quá độ dài cho phép**
  - **Điều kiện kích hoạt**: Tại Bước 2, người dùng nhấn gửi khi ô nội dung trống hoặc chỉ chứa khoảng trắng.
  - **Xử lý**: Hệ thống chặn gửi dữ liệu và hiển thị thông báo *"Vui lòng nhập nội dung câu hỏi trước khi gửi"*.
- **EF-02: Người dùng chưa ghi danh gửi yêu cầu bình luận trực tiếp qua API**
  - **Điều kiện kích hoạt**: Học viên chưa ghi danh khóa học gửi request `POST` tới endpoint tạo bình luận.
  - **Xử lý**: Server kiểm tra quyền ghi danh (`HT-NFR2`) và từ chối với mã lỗi `403 Forbidden`.

---

## 9. Đặc tả Use Case: Quản lý khóa học của giảng viên (`HT-09`)

### 9.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-09` |
| **Tên Use Case** | Quản lý khóa học của giảng viên |
| **Tác nhân chính** | Giảng viên (`Teacher`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép giảng viên xem danh sách các khóa học do chính mình tạo, tạo khóa học mới, chỉnh sửa thông tin khóa học (tiêu đề, danh mục, mô tả, thời gian làm bài trắc nghiệm `quiz_minutes`) và xóa mềm khóa học. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Người dùng đã đăng nhập với tài khoản có vai trò `teacher` và đang hoạt động.<br>2. Đối với thao tác sửa/xóa: Giảng viên phải là chủ sở hữu khóa học (`course.teacher_id == current_user.id`, `HT-NFR3`). |
| **Hậu điều kiện** | • **Thành công**: Khóa học mới được tạo (gán tự động `teacher_id = current_user.id`) hoặc cập nhật/xóa mềm trong bảng `courses`.<br>• **Thất bại**: Không thay đổi dữ liệu CSDL và hiển thị thông báo lỗi. |

### 9.2. Luồng sự kiện chính (Main Flow – Tạo mới khóa học)

| Bước | Tác nhân (Giảng viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập phân hệ **"Quản lý khóa học của tôi"**. | Truy vấn và hiển thị danh sách các khóa học có `teacher_id == current_user.id` (chưa bị xóa mềm) kèm số bài học, số học viên ghi danh và trạng thái hiển thị. |
| **2** | Nhấn nút **"Tạo khóa học mới"**. | Tải danh sách danh mục (`categories`) và hiển thị biểu mẫu tạo khóa học gồm: `Tên khóa học`, `Danh mục`, `Mô tả chi tiết`, `Thời gian làm bài trắc nghiệm (phút)`. |
| **3** | Nhập đầy đủ thông tin và nhấn **"Lưu khóa học"**. | Kiểm tra tính hợp lệ dữ liệu (`title` bắt buộc, `category_id` tồn tại trong `categories`, `quiz_minutes` là số nguyên dương). |
| **4** | — | Lưu bản ghi khóa học mới vào bảng `courses` với `teacher_id = current_user.id` và trạng thái khởi tạo (`pending` hoặc `public` theo cấu hình), thông báo *"Tạo khóa học thành công!"*. |

### 9.3. Luồng phụ (Alternative Flows)

- **AF-01: Chỉnh sửa thông tin khóa học**
  - **Điều kiện kích hoạt**: Tại Bước 1, giảng viên nhấn nút **"Sửa"** ở một khóa học của mình.
  - **Xử lý**: Hệ thống kiểm tra quyền sở hữu (`HT-NFR3`), tải dữ liệu hiện tại lên biểu mẫu, cho phép giảng viên cập nhật tiêu đề, danh mục, mô tả, thời lượng thi và lưu lại vào bảng `courses`.
- **AF-02: Xóa mềm khóa học**
  - **Điều kiện kích hoạt**: Tại Bước 1, giảng viên nhấn nút **"Xóa"** ở một khóa học của mình và xác nhận.
  - **Xử lý**: Hệ thống kiểm tra quyền sở hữu (`HT-NFR3`) và cập nhật `deleted_at = NOW()` để ẩn khóa học khỏi hệ thống mà vẫn bảo toàn dữ liệu lịch sử học tập liên quan.

### 9.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Giảng viên truy cập/chỉnh sửa khóa học của giảng viên khác (`HT-NFR3`)**
  - **Điều kiện kích hoạt**: Giảng viên A nhập trực tiếp URL `/teacher/courses/{id}/edit` của khóa học do Giảng viên B tạo.
  - **Xử lý**: Server chặn yêu cầu (`403 Forbidden`) và thông báo *"Bạn không có quyền thao tác trên khóa học của giảng viên khác"*.
- **EF-02: Dữ liệu đầu vào không hợp lệ**
  - **Điều kiện kích hoạt**: Bỏ trống tên khóa học, không chọn danh mục hoặc nhập thời gian làm bài $\le 0$.
  - **Xử lý**: Hiển thị thông báo lỗi Validation ngay tại trường tương ứng.

---

## 10. Đặc tả Use Case: Quản lý bài học (`HT-10`)

### 10.1. Bảng thông tin tổng quan

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

### 10.2. Luồng sự kiện chính (Main Flow – Tạo mới bài học)

| Bước | Tác nhân (Giảng viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập trang **"Quản lý khóa học của tôi"** (`HT-09`) và chọn **"Quản lý bài học"** tại một khóa học. | Kiểm tra quyền sở hữu khóa học (`HT-NFR3`), truy vấn và hiển thị danh sách các bài học chưa bị xóa mềm sắp xếp theo `position` kèm nút **"Thêm bài học mới"**. |
| **2** | Nhấn nút **"Thêm bài học mới"**. | Hiển thị biểu mẫu tạo bài học gồm các trường: Tiêu đề bài học (`title`), Nội dung chi tiết (`content`), Đường dẫn video YouTube (`video_url`), Tệp tài liệu PDF (`pdf_file`) và Thứ tự bài học (`position`). |
| **3** | Nhập tiêu đề, soạn nội dung văn bản, dán link video YouTube và/hoặc chọn tệp PDF từ máy tính, sau đó nhấn **"Lưu bài học"**. | Kiểm tra tính hợp lệ của dữ liệu đầu vào: tiêu đề không được để trống, đường dẫn video đúng định dạng (nếu có nhập), tệp đính kèm đúng định dạng `.pdf` và dung lượng $\le 5\text{ MB}$ (nếu có tải lên). |
| **4** | — | Lưu tệp PDF vào thư mục lưu trữ của server (`storage/app/public/lessons`) và sinh đường dẫn `pdf_url`, sau đó ghi bản ghi mới vào bảng `lessons`. |
| **5** | — | Hiển thị thông báo *"Thêm bài học thành công"* và cập nhật lại danh sách bài học của khóa học. |

### 10.3. Luồng phụ (Alternative Flows)

- **AF-01: Chỉnh sửa nội dung bài học**
  - **Điều kiện kích hoạt**: Tại Bước 1, giảng viên nhấn nút **"Sửa"** ở một bài học hiện có.
  - **Xử lý**:
    1. Hệ thống tải toàn bộ dữ liệu hiện tại của bài học (tiêu đề, nội dung, link video, tên tệp PDF hiện tại, thứ tự) lên biểu mẫu chỉnh sửa.
    2. Giảng viên cập nhật thông tin hoặc tải lên tệp PDF mới thay thế tệp cũ, sau đó nhấn **"Cập nhật bài học"**.
    3. Hệ thống kiểm tra hợp lệ như Bước 3 của luồng chính, cập nhật bản ghi trong bảng `lessons` (xóa tệp PDF cũ trên server nếu có tệp mới thay thế) và thông báo *"Cập nhật bài học thành công"*.
- **AF-02: Xóa bài học (Xóa mềm)**
  - **Điều kiện kích hoạt**: Tại Bước 1, giảng viên nhấn nút **"Xóa"** ở một bài học.
  - **Xử lý**:
    1. Hệ thống hiển thị hộp thoại cảnh báo xác nhận xóa bài học.
    2. Giảng viên nhấn **"Xác nhận xóa"**.
    3. Hệ thống thực hiện **xóa mềm** bằng cách cập nhật trường `deleted_at = NOW()` trong bảng `lessons` (không xóa vật lý dữ liệu nhằm bảo toàn lịch sử tiến độ học tập `lesson_progress`), sau đó ẩn bài học khỏi danh sách hiển thị.
- **AF-03: Sắp xếp lại thứ tự bài học**
  - **Điều kiện kích hoạt**: Tại Bước 1, giảng viên điều chỉnh chỉ số thứ tự (`position`) của các bài học và nhấn **"Lưu thứ tự"**.
  - **Xử lý**: Hệ thống cập nhật lại giá trị `position` cho các bản ghi tương ứng trong bảng `lessons` và sắp xếp lại danh sách bài học theo thứ tự mới.

### 10.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Tệp PDF vượt quá dung lượng $5\text{ MB}$ hoặc sai định dạng**
  - **Điều kiện kích hoạt**: Tại Bước 3, giảng viên chọn tệp không phải định dạng PDF (kiểm tra cả đuôi tệp `.pdf` lẫn MIME type `application/pdf`) hoặc tệp có dung lượng $> 5\text{ MB}$.
  - **Xử lý**: Hệ thống hủy quá trình tải lên, hiển thị thông báo lỗi *"Tệp tài liệu phải có định dạng PDF và dung lượng không vượt quá $5\text{ MB}$"*, giữ nguyên các nội dung văn bản giảng viên đã nhập để chọn lại tệp.
- **EF-02: Vi phạm quyền sở hữu khóa học (`HT-NFR3`)**
  - **Điều kiện kích hoạt**: Giảng viên cố tình gọi API hoặc truy cập đường dẫn thêm/sửa/xóa bài học thuộc khóa học của giảng viên khác (`course.teacher_id != current_user.id`).
  - **Xử lý**: Server từ chối xử lý (`403 Forbidden`), hiển thị thông báo *"Bạn không có quyền quản lý bài học của khóa học này"* và điều hướng về danh sách khóa học của giảng viên (`HT-09`).
- **EF-03: Thiếu thông tin bắt buộc hoặc sai định dạng URL video**
  - **Điều kiện kích hoạt**: Tại Bước 3, giảng viên bỏ trống tiêu đề bài học hoặc nhập đường dẫn video không hợp lệ.
  - **Xử lý**: Hệ thống đánh dấu lỗi ngay tại trường nhập liệu tương ứng, hiển thị thông báo hướng dẫn định dạng chuẩn và yêu cầu giảng viên chỉnh sửa trước khi lưu.

---

## 11. Đặc tả Use Case: Quản lý ngân hàng câu hỏi trắc nghiệm (`HT-11`)

### 11.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-11` |
| **Tên Use Case** | Quản lý ngân hàng câu hỏi trắc nghiệm |
| **Tác nhân chính** | Giảng viên (`Teacher`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép giảng viên thêm mới, xem danh sách, chỉnh sửa và xóa các câu hỏi trắc nghiệm (gồm nội dung câu hỏi, 4 phương án A/B/C/D và đáp án đúng) cho từng khóa học do mình phụ trách. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Giảng viên đã đăng nhập với vai trò `teacher`.<br>2. Giảng viên là chủ sở hữu hợp lệ của khóa học đang thao tác (`course.teacher_id == current_user.id`, `HT-NFR3`). |
| **Hậu điều kiện** | • **Thành công**: Bản ghi câu hỏi được thêm mới, cập nhật hoặc xóa trong bảng `questions` gắn với `course_id` tương ứng.<br>• **Thất bại**: Không thay đổi dữ liệu CSDL và hiển thị thông báo lỗi. |

### 11.2. Luồng sự kiện chính (Main Flow – Thêm câu hỏi mới)

| Bước | Tác nhân (Giảng viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Tại trang quản lý khóa học (`HT-09`), chọn **"Quản lý câu hỏi trắc nghiệm"**. | Kiểm tra quyền sở hữu khóa học (`HT-NFR3`), tải danh sách các câu hỏi hiện có của khóa học từ bảng `questions`. |
| **2** | Nhấn nút **"Thêm câu hỏi mới"**. | Hiển thị biểu mẫu nhập câu hỏi gồm: `Nội dung câu hỏi`, `Đáp án A`, `Đáp án B`, `Đáp án C`, `Đáp án D`, `Đáp án đúng` (chọn một trong `A, B, C, D`) và `Điểm số`. |
| **3** | Điền đầy đủ nội dung câu hỏi, 4 phương án, chọn đáp án đúng và nhấn **"Lưu câu hỏi"**. | Kiểm tra dữ liệu đầu vào: không được bỏ trống nội dung câu hỏi hoặc bất kỳ phương án nào; `correct_option` phải thuộc tập `{A, B, C, D}`. |
| **4** | — | Lưu bản ghi mới vào bảng `questions`, cập nhật danh sách câu hỏi và thông báo *"Thêm câu hỏi trắc nghiệm thành công!"*. |

### 11.3. Luồng phụ (Alternative Flows)

- **AF-01: Chỉnh sửa câu hỏi trắc nghiệm**
  - **Điều kiện kích hoạt**: Giảng viên nhấn nút **"Sửa"** ở một câu hỏi trong danh sách.
  - **Xử lý**: Hệ thống hiển thị dữ liệu hiện tại của câu hỏi, cho phép giảng viên chỉnh sửa nội dung các phương án hoặc thay đổi đáp án đúng và lưu cập nhật vào bảng `questions`.
- **AF-02: Xóa câu hỏi trắc nghiệm**
  - **Điều kiện kích hoạt**: Giảng viên nhấn nút **"Xóa"** ở một câu hỏi và xác nhận trên hộp thoại.
  - **Xử lý**: Hệ thống xóa câu hỏi khỏi danh sách đề thi của khóa học và thông báo *"Đã xóa câu hỏi thành công"*.

### 11.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Vi phạm quyền sở hữu khóa học (`HT-NFR3`)**
  - **Điều kiện kích hoạt**: Giảng viên truy cập đường dẫn quản lý câu hỏi của khóa học thuộc về giảng viên khác.
  - **Xử lý**: Server chặn yêu cầu (`403 Forbidden`) và chuyển hướng về danh sách khóa học của giảng viên.
- **EF-02: Bỏ trống phương án trả lời hoặc chưa chọn đáp án đúng**
  - **Điều kiện kích hoạt**: Tại Bước 3, một trong 4 phương án A/B/C/D bị bỏ trống hoặc chưa chọn đáp án đúng.
  - **Xử lý**: Hiển thị lỗi Validation ngay tại biểu mẫu và yêu cầu bổ sung trước khi lưu.

---

## 12. Đặc tả Use Case: Thống kê kết quả học tập và xuất báo cáo CSV (`HT-12` – Cộng điểm)

### 12.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-12` |
| **Tên Use Case** | Thống kê kết quả học tập và xuất báo cáo CSV *(Chức năng cộng điểm)* |
| **Tác nhân chính** | Giảng viên (`Teacher`) |
| **Tác nhân phụ** | Quản trị viên (`Admin`) |
| **Mô tả tóm tắt** | Cho phép giảng viên theo dõi bảng tổng hợp tiến độ học tập (`%` hoàn thành bài học) và điểm số trắc nghiệm (số lượt thi, điểm cao nhất) của tất cả học viên đã ghi danh trong khóa học của mình, đồng thời xuất toàn bộ bảng thống kê ra tệp `.csv` chuẩn UTF-8. |
| **Mức độ ưu tiên** | Thấp / Mở rộng (Chức năng cộng điểm) |
| **Tiền điều kiện** | 1. Giảng viên đã đăng nhập và là chủ sở hữu hợp lệ của khóa học (`HT-NFR3`).<br>2. Khóa học tồn tại trong hệ thống. |
| **Hậu điều kiện** | • **Thành công**: Hiển thị bảng thống kê chi tiết từng học viên trên giao diện web; khi bấm xuất CSV, trình duyệt tải về tệp `.csv` có mã hóa `UTF-8 BOM` hiển thị chuẩn tiếng Việt trong Microsoft Excel.<br>• **Thất bại**: Từ chối truy cập nếu không phải chủ sở hữu khóa học. |

### 12.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Giảng viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Tại trang quản lý khóa học (`HT-09`), nhấn nút **"Thống kê & Kết quả"**. | Kiểm tra quyền sở hữu khóa học (`HT-NFR3`). |
| **2** | — | Truy vấn danh sách học viên từ `enrollments`, tính tỷ lệ `%` bài học đã hoàn thành từ `lesson_progress` và tổng hợp số lượt thi, điểm cao nhất (`MAX(score)`), thời gian nộp gần nhất từ `quiz_attempts`. |
| **3** | Xem bảng tổng hợp chỉ số chung (Tổng học viên, Tiến độ trung bình, Điểm trung bình, Tỷ lệ đạt) và chi tiết từng học viên. | Hiển thị giao diện báo cáo thống kê trực quan kèm nút **"Xuất file CSV"**. |
| **4** | Nhấn nút **"Xuất file CSV"**. | Khởi tạo luồng phản hồi `StreamedResponse` với header `Content-Type: text/csv; charset=UTF-8`, chèn ký tự `UTF-8 BOM (\xEF\xBB\xBF)` và xuất toàn bộ dữ liệu bảng ra tệp `.csv` để tải về máy. |

### 12.3. Luồng phụ (Alternative Flows)

- **AF-01: Khóa học chưa có học viên nào ghi danh**
  - **Điều kiện kích hoạt**: Tại Bước 2, bảng `enrollments` chưa có bản ghi nào cho khóa học này.
  - **Xử lý**: Hệ thống hiển thị các chỉ số tổng quan ở mức `0` và thông báo *"Khóa học hiện chưa có học viên nào ghi danh"*.

### 12.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Giảng viên xuất báo cáo CSV của khóa học không thuộc quyền quản lý (`HT-NFR3`)**
  - **Điều kiện kích hoạt**: Giảng viên gọi trực tiếp URL xuất CSV của khóa học thuộc về giảng viên khác.
  - **Xử lý**: Server chặn truy cập (`403 Forbidden`) và không trả về dữ liệu.

---

## 13. Đặc tả Use Case: Quản lý người dùng và phân quyền hệ thống (`HT-14`)

### 13.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-14` |
| **Tên Use Case** | Quản lý người dùng và phân quyền hệ thống |
| **Tác nhân chính** | Quản trị viên (`Admin`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép quản trị viên xem danh sách toàn bộ tài khoản trên hệ thống, tìm kiếm/lọc theo vai trò (`admin`, `teacher`, `student`), thay đổi vai trò cấp quyền (ví dụ nâng cấp `student` lên `teacher`) và khóa hoặc mở khóa tài khoản vi phạm. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Người dùng đã đăng nhập với tài khoản có vai trò `admin`. |
| **Hậu điều kiện** | • **Thành công**: Vai trò (`role`) hoặc trạng thái hoạt động (`status` / `is_active`) của người dùng được cập nhật trong bảng `users`.<br>• **Thất bại**: Ngăn chặn thao tác nếu quản trị viên tự khóa hoặc tự hạ quyền chính tài khoản của mình. |

### 13.2. Luồng sự kiện chính (Main Flow)

| Bước | Tác nhân (Quản trị viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập phân hệ **"Quản lý người dùng"** trong trang quản trị Admin. | Kiểm tra Middleware `role:admin`, truy vấn danh sách người dùng từ bảng `users` (hỗ trợ tìm kiếm theo tên/email, lọc theo vai trò và phân trang). |
| **2** | Chọn tài khoản cần điều chỉnh quyền, chọn vai trò mới (`student` / `teacher` / `admin`) và nhấn **"Cập nhật quyền"**. | Kiểm tra tài khoản đích không phải là chính tài khoản Admin đang đăng nhập (`$targetUser->id !== auth()->id()`). |
| **3** | — | Cập nhật cột `role` trong bảng `users` và hiển thị thông báo *"Cập nhật vai trò người dùng thành công!"*. |

### 13.3. Luồng phụ (Alternative Flows)

- **AF-01: Khóa hoặc Mở khóa tài khoản người dùng**
  - **Điều kiện kích hoạt**: Tại Bước 1, Admin nhấn nút **"Khóa tài khoản"** (hoặc **"Mở khóa"**) tại một tài khoản người dùng.
  - **Xử lý**:
    1. Hệ thống kiểm tra tài khoản đích không phải chính Admin đang đăng nhập.
    2. Cập nhật trạng thái tài khoản sang `locked` (hoặc `active`); nếu tài khoản bị khóa đang đăng nhập ở phiên khác, hệ thống vô hiệu hóa quyền truy cập của tài khoản đó.

### 13.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Quản trị viên tự khóa hoặc tự hạ quyền chính mình**
  - **Điều kiện kích hoạt**: Admin gửi yêu cầu khóa hoặc đổi vai trò của chính ID tài khoản đang đăng nhập.
  - **Xử lý**: Server chặn thao tác và hiển thị thông báo lỗi *"Bạn không thể tự thay đổi vai trò hoặc tự khóa tài khoản của chính mình"*.
- **EF-02: Người dùng không phải Admin truy cập trang quản trị**
  - **Điều kiện kích hoạt**: Tài khoản `student` hoặc `teacher` truy cập URL `/admin/users`.
  - **Xử lý**: Middleware phân quyền chặn lại và trả về mã lỗi `403 Forbidden`.

---

## 14. Đặc tả Use Case: Kiểm duyệt khóa học và quản lý danh mục (`HT-15`)

### 14.1. Bảng thông tin tổng quan

| Thuộc tính | Nội dung chi tiết |
| :--- | :--- |
| **Mã Use Case** | `HT-15` |
| **Tên Use Case** | Kiểm duyệt khóa học và quản lý danh mục |
| **Tác nhân chính** | Quản trị viên (`Admin`) |
| **Tác nhân phụ** | Không có |
| **Mô tả tóm tắt** | Cho phép quản trị viên quản lý danh mục khóa học (thêm, sửa, xóa danh mục) và kiểm duyệt toàn bộ khóa học trên hệ thống: xem nội dung chi tiết bài học để kiểm tra chất lượng, duyệt công khai (`published` / `public`) hoặc ẩn (`hidden`) các khóa học vi phạm. |
| **Mức độ ưu tiên** | Cao (Chức năng bắt buộc – Mức B) |
| **Tiền điều kiện** | 1. Người dùng đã đăng nhập với tài khoản có vai trò `admin`. |
| **Hậu điều kiện** | • **Thành công**: Trạng thái khóa học được cập nhật giữa công khai (`published` / `public`) và ẩn (`hidden`) trong bảng `courses`; hoặc dữ liệu danh mục được cập nhật trong bảng `categories`.<br>• **Thất bại**: Ngăn chặn xóa danh mục nếu danh mục đó đang chứa khóa học hoạt động. |

### 14.2. Luồng sự kiện chính (Main Flow – Kiểm duyệt khóa học)

| Bước | Tác nhân (Quản trị viên) | Hệ thống (VHHHN-LMS) |
| :---: | :--- | :--- |
| **1** | Truy cập phân hệ **"Quản lý & Kiểm duyệt khóa học"** trong trang Admin. | Truy vấn toàn bộ khóa học trên hệ thống (bao gồm cả khóa đang công khai lẫn khóa đang ẩn/chờ duyệt) kèm thông tin giảng viên và danh mục. |
| **2** | Nhấn xem chi tiết khóa học (`HT-02`) và vào xem nội dung các bài giảng (`HT-05`) để kiểm tra chất lượng nội dung. | Cho phép tài khoản `admin` truy cập toàn bộ bài giảng mà không cần ghi danh. |
| **3** | Nhấn nút **"Duyệt / Công khai"** (hoặc **"Ẩn khóa học"**) tại khóa học tương ứng. | Gửi yêu cầu `PATCH` / `PUT` kèm `@csrf` lên route quản trị (`admin.courses.approve` hoặc `admin.courses.hide`). |
| **4** | — | Cập nhật cột `status` của khóa học trong bảng `courses` (`published` hoặc `hidden`) và thông báo *"Cập nhật trạng thái khóa học thành công!"*. |

### 14.3. Luồng phụ (Alternative Flows)

- **AF-01: Quản lý danh mục khóa học (Thêm, Sửa, Xóa)**
  - **Điều kiện kích hoạt**: Admin truy cập mục **"Quản lý danh mục"**.
  - **Xử lý**:
    1. **Thêm / Sửa danh mục**: Admin nhập tên danh mục và mô tả, hệ thống kiểm tra tính duy nhất của tên danh mục (`unique:categories,name`) và lưu vào bảng `categories`.
    2. **Xóa danh mục**: Admin nhấn nút **"Xóa"** tại một danh mục chưa có khóa học nào trực thuộc; hệ thống xóa bản ghi khỏi `categories`.

### 14.4. Luồng ngoại lệ (Exception Flows)

- **EF-01: Xóa danh mục đang chứa khóa học**
  - **Điều kiện kích hoạt**: Tại `AF-01`, Admin cố tình xóa một danh mục đang có khóa học liên kết (`courses.category_id`).
  - **Xử lý**: Hệ thống kiểm tra ràng buộc khóa ngoại, hủy thao tác xóa và thông báo *"Không thể xóa danh mục này vì đang có khóa học trực thuộc"*.

---

## 15. Bảng tổng hợp quy tắc nghiệp vụ & Bảng CSDL liên quan (Toàn hệ thống)

| Mã quy tắc | Nội dung quy tắc nghiệp vụ | Áp dụng cho Use Case | Bảng CSDL tác động |
| :--- | :--- | :--- | :--- |
| **BR-01** | Chỉ hiển thị các khóa học ở trạng thái công khai (`published` / `public`) và chưa xóa mềm cho khách vãng lai và học viên. | `HT-01`, `HT-02`, `HT-04` | `courses`, `categories` |
| **BR-02** | Một học viên chỉ được ghi danh tối đa $1$ lần vào cùng một khóa học (`UNIQUE(user_id, course_id)`). | `HT-04` | `enrollments`, `courses` |
| **BR-03** | Học viên bắt buộc phải ghi danh khóa học mới được phép truy cập bài học, tham gia hỏi đáp và làm bài trắc nghiệm (`HT-NFR2`). | `HT-05`, `HT-06`, `HT-08` | `enrollments`, `lessons`, `lesson_comments`, `quiz_attempts` |
| **BR-04** | Thời gian làm bài (`expires_at`) và chấm điểm trắc nghiệm được tính toán hoàn toàn tại server (`HT-NFR1`); tự động thu bài khi hết giờ. | `HT-06` | `courses`, `questions`, `quiz_attempts` |
| **BR-05** | Số lượt làm bài của học viên không được vượt quá giới hạn lượt làm bài tối đa (`max_attempts`, mặc định $3$ lượt); kết quả đánh giá **Đạt** khi $\text{điểm} \ge 5.0$. | `HT-06`, `HT-07` | `quiz_attempts` |
| **BR-06** | Giảng viên chỉ được thao tác CRUD và xuất thống kê trên các khóa học, bài học, câu hỏi do chính mình tạo (`course.teacher_id == current_user.id`, `HT-NFR3`). | `HT-09`, `HT-10`, `HT-11`, `HT-12` | `courses`, `lessons`, `questions` |
| **BR-07** | Tài liệu đính kèm bài học phải là tệp `.pdf` với dung lượng $\le 5\text{ MB}$; thao tác xóa khóa học và bài học áp dụng cơ chế xóa mềm (`deleted_at`). | `HT-09`, `HT-10` | `courses`, `lessons` |
| **BR-08** | Quản trị viên có quyền kiểm duyệt ẩn/công khai mọi khóa học, quản lý danh mục và phân quyền người dùng, nhưng không được tự khóa hoặc tự hạ quyền chính mình. | `HT-14`, `HT-15` | `users`, `courses`, `categories` |