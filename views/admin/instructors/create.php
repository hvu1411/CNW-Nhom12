<?php
$tiêu_đề = "Thêm Giảng viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Thêm Giảng viên mới</h1>
            
            <div class="form-container">
                <form action="index.php?controller=admin&action=create_instructor" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập *</label>
                        <input type="text" id="username" name="username" required 
                               placeholder="Nhập tên đăng nhập (không dấu, không khoảng trắng)">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required 
                               placeholder="Nhập email giảng viên">
                    </div>
                    
                    <div class="form-group">
                        <label for="fullname">Họ tên *</label>
                        <input type="text" id="fullname" name="fullname" required 
                               placeholder="Nhập họ tên đầy đủ">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" 
                               placeholder="Nhập số điện thoại (tùy chọn)">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mật khẩu *</label>
                        <input type="password" id="password" name="password" required minlength="6"
                               placeholder="Nhập mật khẩu (ít nhất 6 ký tự)">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                               placeholder="Nhập lại mật khẩu">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">✅ Tạo giảng viên</button>
                        <a href="index.php?controller=admin&action=list_instructors" class="btn btn-secondary">← Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.form-container {
    max-width: 600px;
}
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>
