<?php
$tiêu_đề = "Đăng nhập - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';

// Retrieve and clear session messages to avoid rendering them multiple times
$lỗi = isset($_SESSION['lỗi']) ? htmlspecialchars($_SESSION['lỗi'], ENT_QUOTES, 'UTF-8') : null;
$thành_công = isset($_SESSION['thành_công']) ? htmlspecialchars($_SESSION['thành_công'], ENT_QUOTES, 'UTF-8') : null;
if (isset($_SESSION['lỗi'])) unset($_SESSION['lỗi']);
if (isset($_SESSION['thành_công'])) unset($_SESSION['thành_công']);

// Generate CSRF token for security (prevents one-time XSS)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng nhập</h2>
            
            <?php if ($lỗi): ?>
                <div class="alert alert-danger" role="alert"><?= $lỗi ?></div>
            <?php endif; ?>
            
            <?php if ($thành_công): ?>
                <div class="alert alert-success" role="alert"><?= $thành_công ?></div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?controller=auth&action=login" class="auth-form" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-group">
                    <label for="username">Tên đăng nhập hoặc Email:</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required 
                        class="form-control"
                        autocomplete="username"
                        spellcheck="false"
                        maxlength="255">
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="form-control"
                        autocomplete="current-password">
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
