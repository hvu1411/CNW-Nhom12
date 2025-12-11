<?php
// filepath: c:\xampp\htdocs\cse485\CNW_nhom12\views\student\course_progress.php
$tiêu_đề = "Tiến độ khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';

// Tối ưu: Lưu trữ các giá trị thường dùng vào biến
$has_course = !empty($khóa_học);
$has_lessons = !empty($danh_sách_bài_học);

if ($has_course) {
    $course_title = htmlspecialchars($khóa_học['title']);
    $instructor_name = htmlspecialchars($khóa_học['tên_giảng_viên']);
}
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tiến độ khóa học</h1>
            
            <?php if ($has_course): ?>
                <h2><?= $course_title ?></h2>
                <p><strong>Giảng viên:</strong> <?= $instructor_name ?></p>
                
                <h3>Danh sách bài học</h3>
                <?php if ($has_lessons): ?>
                    <div style="margin: 2rem 0;">
                        <?php foreach ($danh_sách_bài_học as $bài_học): 
                            // Tối ưu: Xử lý dữ liệu trước khi in ra
                            $lesson_id = $bài_học['id'];
                            $lesson_title = htmlspecialchars($bài_học['title']);
                        ?>
                            <div style="padding: 1rem; background: #f8f9fa; margin-bottom: 1rem; border-radius: 4px;">
                                <h4><?= $lesson_title ?></h4>
                                <a href="index.php?controller=lesson&action=view&id=<?= $lesson_id ?>" 
                                   class="btn btn-small btn-primary">Xem bài học</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Chưa có bài học nào.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Không tìm thấy khóa học.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
