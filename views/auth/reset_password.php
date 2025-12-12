<?php
$tiêu_đề = "Đặt lại mật khẩu - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<style>
    .reset-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .reset-box {
        background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 100%);
        border: 1px solid #00ffff40;
        border-radius: 20px;
        padding: 3rem;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 0 40px rgba(0, 255, 255, 0.2);
    }
    
    .reset-box h2 {
        text-align: center;
        color: #00ffff;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 20px #00ffff;
    }
    
    .reset-box .subtitle {
        text-align: center;
        color: #8866aa;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }
    
    .reset-box .icon {
        text-align: center;
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    
    .password-requirements {
        background: rgba(0, 255, 255, 0.1);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        color: #00ffff;
    }
    
    .password-requirements ul {
        margin: 0.5rem 0 0 1.5rem;
        color: #8866aa;
    }
</style>

<div class="container">
    <div class="reset-container">
        <div class="reset-box">
            <div class="icon">🔑</div>
            <h2>Đặt lại mật khẩu</h2>
            <p class="subtitle">Tạo mật khẩu mới cho tài khoản của bạn</p>
            
            <div class="password-requirements">
                <strong>📋 Yêu cầu mật khẩu:</strong>
                <ul>
                    <li>Tối thiểu 6 ký tự</li>
                    <li>Nên kết hợp chữ và số</li>
                </ul>
            </div>
            
            <form method="POST" action="index.php?controller=auth&action=reset_password&token=<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>" class="auth-form">
                <div class="form-group">
                    <label for="new_password">🔒 Mật khẩu mới:</label>
                    <input type="password" id="new_password" name="new_password" required class="form-control" 
                           placeholder="Nhập mật khẩu mới..." minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">🔒 Xác nhận mật khẩu:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="form-control" 
                           placeholder="Nhập lại mật khẩu mới..." minlength="6">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    ✅ Đặt lại mật khẩu
                </button>
            </form>
            
            <div class="auth-footer" style="margin-top: 2rem;">
                <p>Quay lại <a href="index.php?controller=auth&action=login">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
