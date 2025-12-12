<?php
$tiêu_đề = "Quên mật khẩu - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<style>
    .forgot-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .forgot-box {
        background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 100%);
        border: 1px solid #ff00ff40;
        border-radius: 20px;
        padding: 3rem;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 0 40px rgba(255, 0, 255, 0.2);
    }
    
    .forgot-box h2 {
        text-align: center;
        color: #00ffff;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 20px #00ffff;
    }
    
    .forgot-box .subtitle {
        text-align: center;
        color: #8866aa;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }
    
    .forgot-box .icon {
        text-align: center;
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    
    .reset-link-box {
        background: rgba(0, 255, 255, 0.1);
        border: 1px solid #00ffff;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        word-break: break-all;
    }
    
    .reset-link-box label {
        display: block;
        color: #00ffff;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .reset-link-box a {
        color: #ff00ff;
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .reset-link-box a:hover {
        text-decoration: underline;
        text-shadow: 0 0 10px #ff00ff;
    }
    
    .note-box {
        background: rgba(255, 170, 0, 0.1);
        border: 1px solid #ffaa00;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: #ffaa00;
    }
    
    .note-box strong {
        display: block;
        margin-bottom: 0.5rem;
    }
</style>

<div class="container">
    <div class="forgot-container">
        <div class="forgot-box">
            <div class="icon">🔐</div>
            <h2>Quên mật khẩu</h2>
            <p class="subtitle">Nhập email để đặt lại mật khẩu của bạn</p>
            
            <form method="POST" action="index.php?controller=auth&action=forgot_password" class="auth-form">
                <div class="form-group">
                    <label for="email">📧 Email đăng ký:</label>
                    <input type="email" id="email" name="email" required class="form-control" 
                           placeholder="Nhập email của bạn...">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    🔗 Tạo link đặt lại mật khẩu
                </button>
            </form>
            
            <?php if (isset($_SESSION['reset_token'])): ?>
                <div class="reset-link-box">
                    <label>✅ Link đặt lại mật khẩu:</label>
                    <a href="index.php?controller=auth&action=reset_password&token=<?php echo $_SESSION['reset_token']; ?>">
                        Nhấn vào đây để đặt lại mật khẩu
                    </a>
                </div>
                <?php unset($_SESSION['reset_token']); ?>
            <?php endif; ?>
            
            <div class="note-box">
                <strong>⚠️ Lưu ý:</strong>
                • Link đặt lại mật khẩu chỉ có hiệu lực trong 1 giờ<br>
                • Chức năng này không áp dụng cho tài khoản Admin<br>
                • Trong thực tế, link sẽ được gửi qua email
            </div>
            
            <div class="auth-footer" style="margin-top: 2rem;">
                <p>Đã nhớ mật khẩu? <a href="index.php?controller=auth&action=login">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
