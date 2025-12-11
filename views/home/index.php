<?php
$tiêu_đề = "Trang chủ - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <section class="hero">
        <h1>Chào mừng đến với Hệ thống Khóa học Online</h1>
        <p>Học tập mọi lúc, mọi nơi với hàng trăm khóa học chất lượng cao</p>
        <div class="hero-buttons">
            <?php if (empty($_SESSION['đã_đăng_nhập'])): ?>
                <a href="index.php?controller=auth&action=register" class="btn btn-primary">Đăng ký ngay</a>
            <?php endif; ?>
            <a href="index.php?controller=course&action=index" class="btn btn-primary">Xem khóa học</a>
        </div>
    </section>

    <section class="categories">
        <h2>Danh mục khóa học</h2>
        <div class="category-grid">
            <?php if (!empty($danh_sách_danh_mục)): ?>
                <?php foreach ($danh_sách_danh_mục as $danh_mục): 
                    $cat_id = $danh_mục['id'];
                    $cat_name = htmlspecialchars($danh_mục['name']);
                    $cat_desc = htmlspecialchars($danh_mục['description']);
                ?>
                    <div class="category-card">
                        <h3><?= $cat_name ?></h3>
                        <p><?= $cat_desc ?></p>
                        <a href="index.php?controller=course&action=index&category_id=<?= $cat_id ?>" class="btn btn-small">Xem khóa học</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Chưa có danh mục nào.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="courses">
        <h2>Khóa học nổi bật</h2>
        <div class="course-grid">
            <?php if (!empty($danh_sách_khóa_học)): 
                $max_show = 6;
                $shown = 0;
                foreach ($danh_sách_khóa_học as $khóa_học):
                    if ($shown >= $max_show) break;
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
            <?php $shown++; endforeach; ?>
            <?php else: ?>
                <p>Chưa có khóa học nào.</p>
            <?php endif; ?>
        </div>
        <div class="text-center">
            <a href="index.php?controller=course&action=index" class="btn btn-primary">Xem tất cả khóa học</a>
        </div>
    </section>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
