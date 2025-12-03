# 🚀 Quick Start Guide - 5 phút để chạy hệ thống

## Yêu cầu tối thiểu
- XAMPP hoặc WAMP đã cài đặt
- Trình duyệt web hiện đại

## Bước 1: Copy files (30 giây)
```bash
# Copy thư mục onlinecourse vào htdocs
Đường dẫn: C:\xampp\htdocs\onlinecourse (Windows)
Hoặc: /opt/lampp/htdocs/onlinecourse (Linux)
```

## Bước 2: Khởi động services (30 giây)
1. Mở XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**

## Bước 3: Tạo database (1 phút)
1. Truy cập: http://localhost/phpmyadmin
2. Click tab **"Import"**
3. Chọn file: `onlinecourse/database.sql`
4. Click **"Go"**
5. ✅ Database `onlinecourse` đã được tạo!

## Bước 4: Cấu hình (1 phút)
Mở file `onlinecourse/config/Database.php`:

```php
private $máy_chủ = 'localhost';         // ✅ OK
private $tên_csdl = 'onlinecourse';     // ✅ OK
private $tên_người_dùng = 'root';       // ✅ OK cho XAMPP
private $mật_khẩu = '';                 // ✅ OK cho XAMPP
```

> **Lưu ý MAMP users:** Đổi password thành `'root'`

## Bước 5: Truy cập (10 giây)
Mở trình duyệt và truy cập:
```
http://localhost/onlinecourse
```

🎉 **Xong!** Bạn sẽ thấy trang chủ với danh sách khóa học.

---

## 🔐 Đăng nhập ngay

### Tài khoản Admin
```
URL: http://localhost/onlinecourse/index.php?controller=auth&action=login
Username: admin
Password: admin123
```
**Sau khi đăng nhập:** Dashboard quản trị với thống kê

### Tài khoản Giảng viên
```
Username: giaovien1
Password: giaovien123
```
**Sau khi đăng nhập:** Dashboard giảng viên, có thể tạo khóa học

### Tài khoản Học viên
```
Username: hocvien1
Password: hocvien123
```
**Sau khi đăng nhập:** Dashboard học viên, có thể đăng ký khóa học

---

## 🎯 Test nhanh các chức năng

### Test 1: Học viên đăng ký khóa học (2 phút)
1. Đăng nhập với `hocvien1/hocvien123`
2. Click "Khóa học" trên menu
3. Chọn khóa học "PHP & MySQL từ cơ bản đến nâng cao"
4. Click "Đăng ký khóa học"
5. ✅ Thành công! Xem trong "Khóa học của tôi"

### Test 2: Giảng viên tạo khóa học (3 phút)
1. Đăng nhập với `giaovien1/giaovien123`
2. Click "Tạo khóa học mới"
3. Điền thông tin:
   - Tên: "Khóa học Test"
   - Mô tả: "Mô tả test"
   - Danh mục: Chọn bất kỳ
   - Giá: 100000
4. Click "Tạo khóa học"
5. ✅ Thành công! Khóa học mới xuất hiện

### Test 3: Admin quản lý danh mục (2 phút)
1. Đăng nhập với `admin/admin123`
2. Click "Quản lý danh mục"
3. Click "Tạo danh mục mới"
4. Nhập tên: "Danh mục Test"
5. Click "Tạo danh mục"
6. ✅ Thành công! Danh mục mới xuất hiện

---

## ❓ Gặp vấn đề?

### Lỗi "Không kết nối được database"
```bash
# Kiểm tra MySQL đang chạy
- XAMPP Control Panel → MySQL phải có chữ "Running"
- Nếu không chạy, click Start
```

### Lỗi "404 Not Found"
```bash
# Kiểm tra đường dẫn
- Đúng: http://localhost/onlinecourse
- Sai: http://localhost/onlinecourse/ (dư dấu /)
- Sai: file:///C:/xampp/htdocs/onlinecourse (không qua web server)
```

### Lỗi "Database không tồn tại"
```bash
# Tạo lại database
1. Vào phpMyAdmin
2. Click "New" → Tên: onlinecourse
3. Import lại file database.sql
```

### Layout bị vỡ, không có CSS
```bash
# Kiểm tra đường dẫn
- Đảm bảo truy cập qua http://localhost
- Kiểm tra file assets/css/style.css tồn tại
- Hard refresh trình duyệt (Ctrl+F5)
```

---

## 📱 Test trên điện thoại

1. Lấy IP máy tính:
   ```bash
   # Windows
   ipconfig
   # Tìm IPv4 Address: 192.168.x.x
   
   # Linux/Mac
   ifconfig
   # Tìm inet: 192.168.x.x
   ```

2. Truy cập từ điện thoại:
   ```
   http://192.168.x.x/onlinecourse
   ```

3. ✅ Website responsive, hoạt động tốt trên mobile!

---

## 🎓 Bước tiếp theo

### Sau khi đã chạy thành công:

1. **Đọc README.md** - Tìm hiểu chi tiết về hệ thống
2. **Đọc PROJECT_SUMMARY.md** - Hiểu rõ cấu trúc dự án
3. **Đọc code** - Học cách code được tổ chức
4. **Tùy chỉnh** - Thay đổi màu sắc, logo, nội dung
5. **Mở rộng** - Thêm tính năng mới

### Thử nghiệm nâng cao:

- Tạo nhiều khóa học
- Thêm nhiều bài học
- Đăng ký học viên vào khóa học
- Xem thống kê trong admin
- Test các chức năng CRUD
- Thử xóa và chỉnh sửa

---

## 🔧 Tip hữu ích

### Xem lỗi PHP
Thêm vào đầu `index.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Reset database
```bash
# Xóa database cũ và import lại
1. phpMyAdmin → Drop database 'onlinecourse'
2. Import lại file database.sql
3. Tất cả dữ liệu mẫu sẽ được khôi phục
```

### Thay đổi port Apache
Nếu port 80 bị chiếm:
```bash
# XAMPP httpd.conf
Listen 8080

# Truy cập
http://localhost:8080/onlinecourse
```

---

## 💡 Các câu hỏi thường gặp

**Q: Tôi có thể sử dụng với MySQL Workbench?**
A: Có! Import file database.sql vào Workbench.

**Q: Có thể deploy lên hosting?**
A: Có! Upload files và import database.sql trên hosting.

**Q: Có thể đổi tên database?**
A: Có! Đổi tên trong file Database.php và tạo database mới.

**Q: Làm sao tạo thêm tài khoản admin?**
A: Đăng ký tài khoản mới → vào database → sửa role thành 2.

**Q: Có thể xóa dữ liệu mẫu?**
A: Có! Xóa các INSERT trong database.sql trước khi import.

---

## 🎉 Chúc mừng!

Bạn đã chạy thành công hệ thống Quản lý Khóa học Online!

**Thời gian hoàn thành:** ~5 phút ⏱️

**Bước tiếp theo:**
- Khám phá các chức năng
- Đọc tài liệu chi tiết
- Tùy chỉnh theo ý bạn
- Học từ source code

**Cần trợ giúp?**
- Đọc INSTALLATION_GUIDE.md
- Đọc README.md
- Check console log (F12)
- Check Apache error log

---

**Happy Coding! 🚀**
