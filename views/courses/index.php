<?php
$tiêu_đề = "Danh sách khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';

// Tối ưu: Lưu category_id được chọn để tránh gọi $_GET nhiều lần
$selected_category = $_GET['category_id'] ?? '';
?>

<div class="container">
    <h1>Danh sách khóa học</h1>
    
    <div class="filter-section" style="margin: 2rem 0;">
        <h3>Lọc theo danh mục:</h3>
        <select onchange="window.location.href='index.php?controller=course&action=index&category_id='+this.value" class="form-control" style="max-width: 300px;">
            <option value="">Tất cả danh mục</option>
            <?php if (!empty($danh_sách_danh_mục)): ?>
                <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                    <option value="<?= $danh_mục['id'] ?>" <?= ($selected_category == $danh_mục['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($danh_mục['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    
    <?php if (!empty($danh_sách_khóa_học)): ?>
        <div class="course-grid">
            <?php foreach ($danh_sách_khóa_học as $khóa_học): 
                // Tối ưu: Lưu các giá trị vào biến để tránh gọi htmlspecialchars nhiều lần
                $course_id = $khóa_học['id'];
                $course_title = htmlspecialchars($khóa_học['title']);
                $course_image = htmlspecialchars($khóa_học['image'] ?? '');
                $course_instructor = htmlspecialchars($khóa_học['tên_giảng_viên']);
                $course_category = htmlspecialchars($khóa_học['tên_danh_mục']);
                $course_level = htmlspecialchars($khóa_học['level']);
                $course_price = number_format($khóa_học['price'], 0, ',', '.');
            ?>
                <div class="course-card">
                    <div class="course-image">
                        <?php if (!empty($course_image)): ?>
                            <img src="assets/images/<?= $course_image ?>" alt="<?= $course_title ?>" loading="lazy">
                        <?php else: ?>
                            <div class="course-placeholder">Không có ảnh</div>
                        <?php endif; ?>
                    </div>
                    <div class="course-info">
                        <h3><?= $course_title ?></h3>
                        <p class="course-instructor">Giảng viên: <?= $course_instructor ?></p>
                        <p class="course-category">Danh mục: <?= $course_category ?></p>
                        <p class="course-level">Trình độ: <?= $course_level ?></p>
                        <p class="course-price"><?= $course_price ?> VNĐ</p>
                        <a href="index.php?controller=course&action=detail&id=<?= $course_id ?>" class="btn btn-small">Chi tiết</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Không tìm thấy khóa học nào.</p>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
