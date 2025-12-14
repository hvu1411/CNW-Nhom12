<?php
$tiêu_đề = "Dashboard Giảng viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Trang giảng viên</h1>

            <p>Xin chào, <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Giảng viên', ENT_QUOTES, 'UTF-8'); ?></p>

            <div class="instructor-menu">
                <ul>
                    <li>
                        <a href="index.php?controller=instructor&action=my_courses" class="btn btn-primary">
                            📚 Quản lý khóa học của tôi
                        </a>
                    </li>
                </ul>
            </div>

            <div class="info-box">
                <p>Vào mục "Quản lý khóa học của tôi" để tạo, chỉnh sửa khóa học, quản lý bài học, tài liệu và học viên.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
