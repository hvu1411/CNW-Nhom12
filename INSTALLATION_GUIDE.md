# Hướng dẫn Cài đặt Hệ thống Quản lý Khóa học Online

## Tổng quan
Hệ thống quản lý khóa học online được xây dựng hoàn chỉnh với PHP & MySQL theo mô hình MVC, bao gồm 50 files PHP với tổng cộng gần 4000 dòng code.

## Yêu cầu hệ thống

### Phần mềm cần thiết:
- **PHP:** phiên bản 7.4 trở lên
- **MySQL:** phiên bản 5.7 trở lên
- **Web Server:** Apache (khuyến nghị) hoặc Nginx
- **PHP Extensions:**
  - PDO
  - PDO_MySQL
  - mbstring
  - session

### Môi trường phát triển đề xuất:
- XAMPP (Windows/Mac/Linux)
- WAMP (Windows)
- LAMP (Linux)
- MAMP (Mac)

## Cài đặt bước 1: Chuẩn bị môi trường

### Với XAMPP (khuyến nghị cho người mới):

1. Tải và cài đặt XAMPP từ: https://www.apachefriends.org/
2. Khởi động Apache và MySQL từ XAMPP Control Panel
3. Kiểm tra PHP đã hoạt động: truy cập http://localhost

## Cài đặt bước 2: Cài đặt mã nguồn

### Cách 1: Copy thư mục onlinecourse

```bash
# Copy thư mục onlinecourse vào htdocs (XAMPP)
cp -r onlinecourse /path/to/xampp/htdocs/

# Hoặc trên Windows
# Copy thư mục onlinecourse vào C:\xampp\htdocs\
```

### Cách 2: Clone từ Git (nếu có)

```bash
cd /path/to/xampp/htdocs/
git clone <repository-url>
```

## Cài đặt bước 3: Tạo cơ sở dữ liệu

### Phương án A: Sử dụng phpMyAdmin (Dễ nhất)

1. Truy cập phpMyAdmin: http://localhost/phpmyadmin
2. Nhấp vào tab "Import"
3. Chọn file `database.sql` từ thư mục `onlinecourse/`
4. Nhấn "Go" để import
5. Database `onlinecourse` sẽ được tạo tự động

### Phương án B: Sử dụng MySQL Command Line

```bash
# Đăng nhập MySQL
mysql -u root -p

# Hoặc import trực tiếp
mysql -u root -p < /path/to/onlinecourse/database.sql
```

### Phương án C: Tạo thủ công

```sql
-- Tạo database
CREATE DATABASE onlinecourse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Chọn database
USE onlinecourse;

-- Chạy từng câu lệnh trong file database.sql
```

## Cài đặt bước 4: Cấu hình kết nối Database

Mở file `onlinecourse/config/Database.php` và chỉnh sửa:

```php
<?php
class Database
{
    // Thông tin kết nối cơ sở dữ liệu
    private $máy_chủ = 'localhost';        // Giữ nguyên nếu chạy local
    private $tên_csdl = 'onlinecourse';    // Tên database
    private $tên_người_dùng = 'root';      // Username MySQL (mặc định: root)
    private $mật_khẩu = '';                // Password MySQL (mặc định: trống)
    
    // ...
}
```

### Lưu ý quan trọng:
- **XAMPP/WAMP:** Username thường là `root`, password để trống
- **MAMP:** Username là `root`, password là `root`
- **Production:** Thay đổi username và password phù hợp

## Cài đặt bước 5: Cấu hình Apache (mod_rewrite)

### Kiểm tra mod_rewrite đã được bật:

#### Trên XAMPP:
1. Mở file `httpd.conf` (C:\xampp\apache\conf\httpd.conf)
2. Tìm dòng: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Bỏ dấu `#` ở đầu dòng
4. Khởi động lại Apache

#### Trên Linux:
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

### Kiểm tra file .htaccess:

File `.htaccess` đã được tạo sẵn trong thư mục `onlinecourse/`. Nội dung:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```


## Kiểm tra cài đặt

### Bước 1: Truy cập website

Mở trình duyệt và truy cập:
```
http://localhost:3000/onlinecourse/index.php
```

Bạn sẽ thấy trang chủ với danh sách khóa học.

### Bước 2: Đăng nhập với tài khoản mẫu

Hệ thống đã có sẵn 3 tài khoản mẫu:

#### 1. Quản trị viên (Admin)
- **Username:** `admin`
- **Password:** `admin123`
- **URL đăng nhập:** http://localhost/onlinecourse/index.php?controller=auth&action=login

#### 2. Giảng viên (Instructor)
- **Username:** `giaovien1`
- **Password:** `giaovien123`

#### 3. Học viên (Student)
- **Username:** `hocvien1`
- **Password:** `hocvien123`

### Bước 3: Kiểm tra chức năng

#### Với tài khoản Admin:
1. Đăng nhập → Dashboard xuất hiện
2. Quản lý người dùng → Xem danh sách users
3. Quản lý danh mục → Tạo/Sửa/Xóa categories
4. Xem thống kê

#### Với tài khoản Giảng viên:
1. Đăng nhập → Dashboard giảng viên
2. Tạo khóa học mới
3. Thêm bài học cho khóa học
4. Xem danh sách học viên

#### Với tài khoản Học viên:
1. Đăng nhập → Dashboard học viên
2. Xem danh sách khóa học
3. Đăng ký khóa học
4. Xem bài học

## Xử lý lỗi thường gặp

### Lỗi 1: "Lỗi kết nối cơ sở dữ liệu"

**Nguyên nhân:**
- Database chưa được tạo
- Thông tin kết nối sai
- MySQL service chưa chạy

**Giải pháp:**
```bash
# Kiểm tra MySQL đang chạy
# XAMPP: Mở XAMPP Control Panel, start MySQL

```

### Lỗi 2: "404 Not Found" hoặc routing không hoạt động

**Nguyên nhân:**
- mod_rewrite chưa được bật
- File .htaccess bị thiếu

**Giải pháp:**
1. Kiểm tra file `.htaccess` tồn tại trong thư mục `onlinecourse/`
2. Bật mod_rewrite (xem bước 5)
3. Restart Apache

### Lỗi 3: "Call to undefined function password_hash()"

**Nguyên nhân:**
- PHP version quá cũ (< 5.5)

**Giải pháp:**
- Nâng cấp PHP lên phiên bản 7.4 trở lên

### Lỗi 4: Không đăng nhập được

**Nguyên nhân:**
- Session không hoạt động
- Cookie bị chặn

**Giải pháp:**
```php
// Kiểm tra session trong php.ini
session.auto_start = 0
session.save_path = "/tmp"

// Restart Apache sau khi sửa
```

### Lỗi 5: Layout bị vỡ, không có CSS

**Nguyên nhân:**
- Đường dẫn file CSS sai
- Truy cập không đúng URL

**Giải pháp:**
- Đảm bảo truy cập: `http://localhost/onlinecourse` (không phải `file:///`)
- Kiểm tra file `assets/css/style.css` tồn tại

## Kiểm tra hoàn tất

Nếu tất cả bước trên thành công, bạn sẽ có:

✅ Trang chủ hiển thị danh sách khóa học
✅ Đăng nhập với 3 vai trò khác nhau
✅ Dashboard riêng cho từng vai trò
✅ Chức năng CRUD hoạt động
✅ CSS/JS load đúng
✅ Routing hoạt động

## Tùy chỉnh

### Thay đổi thông tin database:

Edit file `config/Database.php`:
```php
private $máy_chủ = 'your-host';
private $tên_csdl = 'your-database';
private $tên_người_dùng = 'your-username';
private $mật_khẩu = 'your-password';
```

### Thay đổi mật khẩu admin:

```sql
-- Tạo mật khẩu mới (hash)
-- Sử dụng online bcrypt generator hoặc PHP

-- Cập nhật trong database
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE username = 'admin';
```

## Bảo mật Production

Khi deploy lên production server:

1. **Thay đổi mật khẩu database**
2. **Xóa hoặc đổi mật khẩu tài khoản mẫu**
3. **Cấu hình HTTPS**
4. **Tắt error display trong PHP:**
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```
5. **Bảo vệ file nhạy cảm:**
   ```apache
   <Files "config/Database.php">
       Require all denied
   </Files>
   ```

## Hỗ trợ

Nếu gặp vấn đề:

1. Kiểm tra log lỗi PHP (XAMPP: xampp/apache/logs/error.log)
2. Bật error reporting tạm thời:
   ```php
   // Thêm vào đầu index.php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
3. Kiểm tra console log trong trình duyệt (F12)

## Phát triển tiếp

Sau khi cài đặt thành công, bạn có thể:

- Thêm chức năng upload file thực tế
- Tích hợp payment gateway
- Thêm hệ thống đánh giá
- Thêm quiz/test
- Tích hợp email notification
- Thêm video streaming

---

**Chúc bạn cài đặt thành công!**

Nếu có câu hỏi hoặc vấn đề, vui lòng tạo issue hoặc liên hệ support.
