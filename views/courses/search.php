<?php
// filepath: c:\xampp\htdocs\cse485\CNW_nhom12\views\courses\search.php
$tiêu_đề = "Tìm kiếm khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';

// Tối ưu: Lưu trữ từ khóa tìm kiếm và xử lý một lần
$search_keyword = htmlspecialchars($từ_khóa ?? '');
$has_search = isset($từ_khóa) && !empty($từ_khóa);
?>

<div class="container">
    <h1>Tìm kiếm khóa học</h1>
    
    <div class="search-form" style="margin: 2rem 0;">
        <form method="GET" action="index.php">
            <input type="hidden" name="controller" value="course">
            <input type="hidden" name="action" value="search">
            <div style="display: flex; gap: 1rem;">
                <input type="text" 
                       name="keyword" 
                       id="search-keyword" 
                       placeholder="Nhập từ khóa tìm kiếm..." 
                       class="form-control" 
                       value="<?= $search_keyword ?>" 
                       style="flex: 1;">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>
    </div>
    
    <?php if ($has_search): ?>
        <h2>Kết quả tìm kiếm cho: "<?= $search_keyword ?>"</h2>
        
        <?php if (!empty($danh_sách_khóa_học)): ?>
            <div class="course-grid">
                <?php foreach ($danh_sách_khóa_học as $khóa_học): 
                    // Tối ưu: Lưu trữ các giá trị đã xử lý vào biến
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
                                <img src="assets/images/<?= $course_image ?>" 
                                     alt="<?= $course_title ?>"
                                     loading="lazy">
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
            <p>Không tìm thấy khóa học nào phù hợp.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
