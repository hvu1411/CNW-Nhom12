<?php
$tiêu_đề = "Sửa Giảng viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>✏️ Sửa thông tin Giảng viên</h1>
            
            <div class="form-container">
                <form action="index.php?controller=admin&action=edit_instructor&id=<?php echo $giảng_viên['id']; ?>" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($giảng_viên['username']); ?>" disabled 
                               style="opacity: 0.7; cursor: not-allowed;">
                        <small>Không thể thay đổi tên đăng nhập</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($giảng_viên['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fullname">Họ tên *</label>
                        <input type="text" id="fullname" name="fullname" required 
                               value="<?php echo htmlspecialchars($giảng_viên['fullname']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($giảng_viên['phone'] ?? ''); ?>">
                    </div>
                    
                    <hr style="border-color: rgba(255,255,255,0.1); margin: 2rem 0;">
                    
                    <h3>🔐 Đổi mật khẩu (để trống nếu không đổi)</h3>
                    
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới</label>
                        <input type="password" id="new_password" name="new_password" minlength="6"
                               placeholder="Nhập mật khẩu mới (ít nhất 6 ký tự)">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới</label>
                        <input type="password" id="confirm_password" name="confirm_password" minlength="6"
                               placeholder="Nhập lại mật khẩu mới">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
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
small {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.85rem;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>
