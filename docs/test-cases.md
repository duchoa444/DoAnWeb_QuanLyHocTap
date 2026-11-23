# Danh sách Kịch bản Kiểm thử (Test Cases)

## 1. Quy ước chung
- **Trạng thái**: Pass / Fail / Pending
- **Môi trường kiểm thử**: Localhost (Chrome / Edge)

## 2. Bảng Test Cases Mẫu (Chức năng HT-06: Làm bài trắc nghiệm)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-01 | HT-06 | 1. Đăng nhập tài khoản Học viên.<br>2. Vào khóa học đã ghi danh và bấm "Làm bài trắc nghiệm".<br>3. Chọn đủ đáp án và bấm "Nộp bài" trước khi hết giờ. | Tài khoản: `hv1@test.com`<br>Đáp án đúng: 8/10 câu | Hệ thống lưu lượt làm bài, chấm điểm tự động (8/10) và hiển thị kết quả ngay lập tức. | Pending |
| TC-02 | HT-06 | 1. Bắt đầu làm bài trắc nghiệm.<br>2. Không chọn đáp án cho 3 câu hỏi.<br>3. Bấm "Nộp bài". | Bỏ trống câu 2, 5, 9.<br>Các câu còn lại đúng hết. | Hệ thống hiển thị cảnh báo xác nhận nộp bài khi còn câu trống; nếu xác nhận, các câu trống tính 0 điểm (tổng 7/10). | Pending |
| TC-03 | HT-06 | 1. Bắt đầu làm bài (thời gian `quiz_minutes` = 15 phút).<br>2. Để đồng hồ đếm ngược về `00:00` mà chưa bấm nộp. | Thời gian làm bài hết hạn | Hệ thống tự động khóa chọn đáp án và tự động nộp bài với các đáp án đã chọn trước đó. | Pending |
| TC-04 | HT-06 | 1. Bắt đầu làm bài.<br>2. Can thiệp API hoặc gửi request nộp bài sau khi thời gian làm bài đã kết thúc quá thời gian cho phép. | Request nộp bài trễ hơn thời điểm bắt đầu + `quiz_minutes` | Backend từ chối ghi nhận điểm mới hoặc đánh dấu bài nộp trễ không hợp lệ theo quy định. | Pending |
| TC-05 | HT-06 | 1. Học viên đã làm hết số lượt tối đa cho phép của bài kiểm tra.<br>2. Truy cập lại trang bài trắc nghiệm và bấm "Làm lại". | Tài khoản đã hết lượt làm bài | Nút "Làm bài" bị vô hiệu hóa (disabled) và hiển thị thông báo "Bạn đã hết lượt làm bài". | Pending |

## 3. Bảng Test Cases (Chức năng HT-03: Đăng ký tài khoản học viên)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-06 | HT-03 | 1. Truy cập trang `/register`.<br>2. Nhập đầy đủ họ tên, email chưa tồn tại, mật khẩu hợp lệ và xác nhận mật khẩu.<br>3. Bấm "Đăng ký". | Tên: `Nguyễn Văn A`<br>Email: `sv_moi@example.com`<br>Pass: `12345678` | Tạo tài khoản thành công với vai trò mặc định là `student`, mật khẩu được băm (hash) trong CSDL và chuyển hướng đăng nhập thành công. | Pending |
| TC-07 | HT-03 | 1. Truy cập trang `/register`.<br>2. Nhập email đã tồn tại trong hệ thống.<br>3. Bấm "Đăng ký". | Email: `teacher01@example.com`<br>Pass: `12345678` | Hệ thống từ chối tạo tài khoản và hiển thị thông báo lỗi bằng tiếng Việt (VD: "Email đã được sử dụng"). | Pending |
| TC-08 | HT-03 | 1. Truy cập trang `/register`.<br>2. Nhập mật khẩu ngắn hơn quy định (dưới 8 ký tự) hoặc mật khẩu xác nhận không khớp.<br>3. Bấm "Đăng ký". | Email: `sv2@example.com`<br>Pass: `123` | Form báo lỗi validate bằng tiếng Việt, không lưu dữ liệu vào CSDL. | Pending |
| TC-09 | HT-03 | 1. Truy cập trang `/register`.<br>2. Bỏ trống các trường bắt buộc hoặc nhập sai định dạng email.<br>3. Bấm "Đăng ký". | Email: `khongphaiemail`<br>Tên: *(trống)* | Hệ thống chặn submit và hiển thị thông báo lỗi tiếng Việt tương ứng từng trường. | Pending |
| TC-10 | HT-03 | 1. Gửi request POST tới route đăng ký kèm tham số giả mạo quyền quản trị (`role=admin` hoặc `role=teacher`). | Payload chứa `role: "admin"` | Backend bỏ qua tham số `role` gửi lên, chỉ tạo tài khoản với vai trò `student`. | Pending |

## 4. Bảng Test Cases (Chức năng HT-04: Ghi danh và % tiến độ học tập)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-11 | HT-04 | 1. Đăng nhập tài khoản Học viên.<br>2. Truy cập chi tiết khóa học có trạng thái `published` và bấm "Ghi danh".<br>3. Quay lại trang khóa học đó và bấm "Ghi danh" lần thứ 2. | Tài khoản: `hv1@test.com`<br>Khóa học: `published` | Lần 1 ghi danh thành công và chuyển hướng về `/my-courses`; lần 2 bị chặn, báo đã ghi danh và không tạo bản ghi trùng trong bảng `enrollments`. | Pending |
| TC-12 | HT-04 | 1. Đăng nhập Học viên đã ghi danh khóa học có 4 bài học.<br>2. Hoàn thành 2/4 bài học.<br>3. Mở trang "Khóa học của tôi" (`/my-courses`). | `completed = 2`<br>`total = 4` | Hiển thị đúng tỷ lệ `2/4 bài` và thanh tiến độ đạt `50%`. | Pending |
| TC-13 | HT-04 | 1. Đăng nhập Học viên đã ghi danh khóa học mới tạo chưa có bài học nào.<br>2. Mở trang "Khóa học của tôi" (`/my-courses`). | `completed = 0`<br>`total = 0` | Hiển thị `0/0 bài` và thanh tiến độ `0%`, hệ thống không phát sinh lỗi chia cho 0 (`DivisionByZeroError`). | Pending |

## 5. Bảng Test Cases (Chức năng HT-05: Học bài và bảo vệ tài liệu HT-NFR2)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-14 | HT-05 | 1. Đăng nhập Học viên đã ghi danh khóa học.<br>2. Mở trang học bài, xem nội dung video/PDF và bấm nút "Đánh dấu hoàn thành".<br>3. Bấm "Đánh dấu hoàn thành" thêm lần nữa. | Tài khoản: `hv1@test.com`<br>Bài học thuộc khóa đã ghi danh | Giao diện hiển thị danh sách bài bên trái, nội dung bên phải; ghi nhận 1 bản ghi hoàn thành vào `lesson_progress`, bấm lại không tạo dữ liệu trùng lặp. | Pending |
| TC-15 | HT-05 (HT-NFR2) | 1. Đăng nhập Học viên **chưa ghi danh** khóa học.<br>2. Nhập trực tiếp URL trang học bài của khóa học đó lên thanh địa chỉ trình duyệt. | Tài khoản chưa ghi danh<br>URL học bài của khóa học | Server từ chối truy cập (trả về lỗi 403 hoặc chuyển hướng kèm thông báo yêu cầu ghi danh trước khi học). | Pending |
| TC-16 | HT-05 (HT-NFR2) | 1. Đăng nhập Học viên **chưa ghi danh** khóa học.<br>2. Truy cập trực tiếp đường dẫn tải tệp PDF đính kèm trong bài học. | Tài khoản chưa ghi danh<br>URL route tải PDF | Server chặn tải tệp (trả về lỗi 403), chỉ cho phép học viên đã ghi danh hoặc giảng viên sở hữu khóa học tải về. | Pending |

## 6. Bảng Test Cases Bổ sung (Chức năng HT-06: Kiểm chứng chống gian lận HT-NFR1)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-17 | HT-06 (HT-NFR1) | 1. Bắt đầu làm bài trắc nghiệm.<br>2. Mở DevTools (`F12`) $\rightarrow$ chọn tab **Network** và kiểm tra dữ liệu phản hồi (Response) tải danh sách câu hỏi. | Phiên làm bài trắc nghiệm đang diễn ra | Dữ liệu trả về từ server chỉ chứa nội dung câu hỏi và 4 lựa chọn A/B/C/D, tuyệt đối không chứa trường đáp án đúng (`correct_option`). | Pending |
| TC-18 | HT-06 (HT-NFR1) | 1. Bắt đầu làm bài (thời gian `quiz_minutes` = 15 phút).<br>2. Chỉnh đồng hồ hệ thống trên máy tính chạy lùi/tiến vượt quá thời hạn rồi bấm "Nộp bài".<br>3. Thử bắt đầu lượt làm bài thứ 4 (khi giới hạn tối đa là 3 lượt). | Đồng hồ máy bị chỉnh sai lệch; số lượt đã làm = 3 | Server đối chiếu thời gian thực trên máy chủ để từ chối bài nộp quá hạn và chặn không cho tạo lượt làm bài thứ 4. | Pending |

## 7. Bảng Test Cases (Chức năng HT-11: Quản lý câu hỏi trắc nghiệm và HT-NFR3)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-19 | HT-11 | 1. Đăng nhập tài khoản Giảng viên.<br>2. Vào quản lý câu hỏi của khóa học thuộc sở hữu, nhập câu hỏi mới kèm 4 đáp án A, B, C, D, chọn đáp án đúng và bấm "Lưu".<br>3. Cập nhật thời gian làm bài (`quiz_minutes`) thành `20` phút. | Nội dung câu hỏi hợp lệ; `correct_option = B`; `quiz_minutes = 20` | Thêm câu hỏi thành công vào danh sách hiển thị; thời gian làm bài của khóa học được cập nhật thành 20 phút. | Pending |
| TC-20 | HT-11 | 1. Tại form thêm câu hỏi, bỏ trống nội dung câu hỏi hoặc thiếu 1 trong 4 đáp án A, B, C, D.<br>2. Bấm "Lưu". | `content`: *(trống)*<br>`option_c`: *(trống)* | Hệ thống chặn lưu dữ liệu và hiển thị thông báo lỗi validate rõ ràng bằng tiếng Việt. | Pending |
| TC-21 | HT-11 (HT-NFR3) | 1. Đăng nhập **Giảng viên A**.<br>2. Thay đổi ID khóa học hoặc ID câu hỏi trên URL/Request sang khóa học thuộc quyền sở hữu của **Giảng viên B** để xem, sửa hoặc xóa. | Tài khoản: `gvA@test.com`<br>ID khóa học thuộc `gvB@test.com` | Server kiểm tra quyền sở hữu (`teacher_id`) và chặn thao tác trái phép với mã lỗi `403 Forbidden`. | Pending |

## 8. Bảng Test Cases (Chức năng HT-01: Đăng nhập và Đăng xuất)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-22 | HT-01 | 1. Truy cập `/login`.<br>2. Nhập đúng email và mật khẩu của tài khoản đang hoạt động (`is_active = true`).<br>3. Bấm "Đăng nhập", sau đó bấm "Đăng xuất". | Email: `hv1@test.com`<br>Pass: `12345678` | Đăng nhập thành công, chuyển hướng đúng trang theo vai trò; khi bấm Đăng xuất thì hủy phiên và quay về trang đăng nhập/khóa học. | Pending |
| TC-23 | HT-01 | 1. Truy cập `/login`.<br>2. Nhập sai mật khẩu hoặc đăng nhập bằng tài khoản đã bị quản trị viên khóa (`is_active = false`).<br>3. Bấm "Đăng nhập". | Email: `locked@test.com`<br>Pass: `12345678` | Từ chối đăng nhập và hiển thị thông báo lỗi bằng tiếng Việt (sai thông tin hoặc tài khoản đã bị khóa). | Pending |

## 9. Bảng Test Cases (Chức năng HT-02: Xem danh sách và tìm kiếm khóa học)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-24 | HT-02 | 1. Truy cập trang `/courses`.<br>2. Quan sát danh sách khóa học hiển thị. | Khóa học `published` và khóa học `hidden`/`pending` | Chỉ hiển thị các khóa học ở trạng thái công khai (`published`), không hiển thị khóa học bị ẩn hoặc chờ duyệt. | Pending |
| TC-25 | HT-02 | 1. Tại trang `/courses`, nhập từ khóa tìm kiếm hoặc chọn lọc theo danh mục.<br>2. Bấm vào một khóa học để xem trang chi tiết (`/courses/{id}`). | Từ khóa: `Laravel`<br>Danh mục: `Lập trình Web` | Lọc đúng danh sách khóa học khớp từ khóa/danh mục; trang chi tiết hiển thị đầy đủ mô tả, giảng viên và danh sách bài học. | Pending |

## 10. Bảng Test Cases (Chức năng HT-07: Hồ sơ cá nhân và lịch sử điểm)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-26 | HT-07 | 1. Đăng nhập tài khoản Học viên, truy cập `/profile`.<br>2. Cập nhật họ tên mới và đổi mật khẩu (nhập đúng mật khẩu cũ, mật khẩu mới hợp lệ). | Tên mới: `Nguyễn Thành Nhân`<br>Pass cũ đúng, Pass mới: `87654321` | Cập nhật thông tin và mật khẩu thành công, hiển thị thông báo xác nhận. | Pending |
| TC-27 | HT-07 | 1. Truy cập `/exam-history` (hoặc mục Lịch sử thi trong hồ sơ).<br>2. Quan sát danh sách các lần làm bài trắc nghiệm. | Tài khoản đã có 2 lượt nộp bài trắc nghiệm | Hiển thị đầy đủ tên khóa học, điểm số từng lần thi, thời gian nộp và điểm cao nhất đạt được. | Pending |

## 11. Bảng Test Cases (Chức năng HT-09: Giảng viên quản lý khóa học)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-28 | HT-09 | 1. Đăng nhập tài khoản Giảng viên, vào `/teacher/courses`.<br>2. Bấm "Tạo khóa học", nhập tiêu đề, mô tả, chọn danh mục và lưu. | Tiêu đề: `Lập trình PHP cơ bản`<br>Danh mục hợp lệ | Tạo khóa học mới thành công gắn với `teacher_id` của giảng viên hiện tại. | Pending |
| TC-29 | HT-09 | 1. Giảng viên A đổi ID trên URL `/teacher/courses/{id}/edit` sang khóa học thuộc Giảng viên B.<br>2. Gửi yêu cầu sửa hoặc xóa. | Tài khoản Giảng viên A, ID khóa học của Giảng viên B | Hệ thống chặn thao tác trái phép với lỗi `403 Forbidden`. | Pending |

## 12. Bảng Test Cases (Chức năng HT-10: Giảng viên quản lý bài học và tệp PDF)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-30 | HT-10 | 1. Tại khóa học của mình, giảng viên thêm bài học mới kèm liên kết video và tải lên tệp tài liệu `.pdf` hợp lệ. | Tiêu đề bài học, URL video, file `tailieu.pdf` (< 10MB) | Lưu bài học và tệp PDF thành công vào thư mục lưu trữ bảo vệ của hệ thống. | Pending |
| TC-31 | HT-10 | 1. Tại form thêm/sửa bài học, bỏ trống tiêu đề hoặc tải lên tệp không đúng định dạng `.pdf` (VD: `.exe`). | File: `script.exe` | Backend từ chối lưu và hiển thị thông báo lỗi validate định dạng tệp bằng tiếng Việt. | Pending |

## 13. Bảng Test Cases (Chức năng HT-14: Quản trị tài khoản và danh mục)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-32 | HT-14 | 1. Đăng nhập tài khoản Admin, vào `/admin/users`.<br>2. Bấm nút khóa/mở khóa trạng thái (`toggle-status`) của một tài khoản học viên/giảng viên. | Tài khoản `admin@test.com`<br>User mục tiêu: `hv1@test.com` | Trạng thái `is_active` được cập nhật thành công; tài khoản bị khóa không thể đăng nhập. | Pending |
| TC-33 | HT-14 | 1. Vào `/admin/categories`, thêm mới một danh mục khóa học, sau đó sửa tên danh mục đó. | Tên danh mục: `Cơ sở dữ liệu` | Thêm và cập nhật danh mục thành công, hiển thị ngay trong danh sách chọn khi tạo khóa học. | Pending |

## 14. Bảng Test Cases (Chức năng HT-15: Quản trị duyệt và ẩn khóa học vi phạm)

| ID | Mã chức năng | Bước | Dữ liệu | Kết quả mong đợi | Kết quả thực tế |
| :--- | :--- | :--- | :--- | :--- | :--- |
| TC-34 | HT-15 | 1. Đăng nhập Admin, vào `/admin/courses`.<br>2. Bấm "Duyệt" (`approve`) một khóa học đang chờ duyệt. | Khóa học trạng thái `pending` | Khóa học chuyển sang trạng thái `published` và xuất hiện công khai tại trang `/courses`. | Pending |
| TC-35 | HT-15 | 1. Tại `/admin/courses`, bấm "Ẩn" (`hide`) một khóa học đang công khai vi phạm nội dung.<br>2. Mở trang `/courses` bằng tài khoản khách/học viên. | Khóa học trạng thái `published` | Khóa học chuyển sang trạng thái `hidden` và biến mất khỏi danh sách khóa học công khai. | Pending |