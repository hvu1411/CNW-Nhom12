<?php
$tiêu_đề = "Chi tiết khóa học - " . ($khóa_học['title'] ?? 'Khóa học');
require_once 'views/layouts/header.php';

// Tối ưu: Lưu trữ các giá trị thường dùng vào biến
$is_logged_in = isset($_SESSION['đã_đăng_nhập']);
$is_student = $is_logged_in && $_SESSION['role'] == 0;
?>

<div class="container">
    <?php if ($khóa_học): 
        // Tối ưu: Lưu trữ các giá trị đã xử lý vào biến
        $course_id = $khóa_học['id'];
        $course_title = htmlspecialchars($khóa_học['title']);
        $course_image = htmlspecialchars($khóa_học['image'] ?? '');
        $course_instructor = htmlspecialchars($khóa_học['tên_giảng_viên']);
        $course_category = htmlspecialchars($khóa_học['tên_danh_mục']);
        $course_level = htmlspecialchars($khóa_học['level']);
        $course_duration = $khóa_học['duration_weeks'];
        $course_price = number_format($khóa_học['price'], 0, ',', '.');
        $course_description = nl2br(htmlspecialchars($khóa_học['description']));
    ?>
        <div class="course-detail">
            <?php if (!empty($course_image)): ?>
                <div class="course-detail-image" style="margin-bottom: 2rem; text-align: center;">
                    <img src="assets/images/<?= $course_image ?>" 
                         alt="<?= $course_title ?>" 
                         style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                         loading="lazy">
                </div>
            <?php endif; ?>
            
            <h1><?= $course_title ?></h1>
            <p><strong>Giảng viên:</strong> <?= $course_instructor ?></p>
            <p><strong>Danh mục:</strong> <?= $course_category ?></p>
            <p><strong>Trình độ:</strong> <?= $course_level ?></p>
            <p><strong>Thời lượng:</strong> <?= $course_duration ?> tuần</p>
            <p><strong>Giá:</strong> <span class="course-price"><?= $course_price ?> VNĐ</span></p>
            
            <div class="course-description">
                <h3>Mô tả khóa học</h3>
                <p><?= $course_description ?></p>
            </div>
            
            <?php if ($is_student): ?>
                <?php if (!$đã_đăng_ký): ?>
                    <form method="POST" action="index.php?controller=enrollment&action=enroll">
                        <input type="hidden" name="course_id" value="<?= $course_id ?>">
                        <button type="submit" class="btn btn-success">Đăng ký khóa học</button>
                    </form>
                <?php else: ?>
                    <p class="alert alert-success">Bạn đã đăng ký khóa học này</p>
                    <a href="index.php?controller=student&action=course_progress&course_id=<?= $course_id ?>" class="btn btn-primary">Xem tiến độ</a>
                <?php endif; ?>
            <?php endif; ?>
            
            <div class="lessons-section" style="margin-top: 2rem;">
                <h3>Danh sách bài học</h3>
                <?php if (!empty($danh_sách_bài_học)): ?>
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ($danh_sách_bài_học as $bài_học): ?>
                            <li style="padding: 0.5rem; border-bottom: 1px solid #ddd;">
                                <?= htmlspecialchars($bài_học['title']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Chưa có bài học nào.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <p>Không tìm thấy khóa học.</p>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
