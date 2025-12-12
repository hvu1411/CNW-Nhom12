<?php
$tiêu_đề = "Đăng nhập - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng nhập</h2>
            <form method="POST" action="index.php?controller=auth&action=login" class="auth-form">
                <div class="form-group">
                    <label for="username">Tên đăng nhập hoặc Email:</label>
                    <input type="text" id="username" name="username" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            </form>
            
            <div class="auth-footer">
                <p><a href="index.php?controller=auth&action=forgot_password" style="color: #ff00ff;">🔐 Quên mật khẩu?</a></p>
                <p>Chưa có tài khoản? <a href="index.php?controller=auth&action=register">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
