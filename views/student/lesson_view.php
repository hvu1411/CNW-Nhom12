<?php
// filepath: c:\xampp\htdocs\cse485\CNW_nhom12\views\student\lesson_view.php
$tiêu_đề = "Bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';

// Tối ưu: Lưu trữ các giá trị thường dùng vào biến
$lesson_title = htmlspecialchars($bài_học['title']);
$course_title = htmlspecialchars($khóa_học['title']);
$course_id = $khóa_học['id'];
$lesson_id = $bài_học['id'];
$has_video = !empty($bài_học['video_url']);
$has_materials = !empty($danh_sách_tài_liệu);

if ($has_video) {
    $video_url = htmlspecialchars($bài_học['video_url']);
}

$lesson_content = nl2br(htmlspecialchars($bài_học['content']));
?>

<div class="container">
    <h1><?= $lesson_title ?></h1>
    <p><strong>Khóa học:</strong> <?= $course_title ?></p>
    
    <?php if ($has_video): ?>
        <div style="margin: 2rem 0;">
            <h3>Video bài học</h3>
            <p>Video URL: <a href="<?= $video_url ?>" target="_blank"><?= $video_url ?></a></p>
        </div>
    <?php endif; ?>
    
    <div style="margin: 2rem 0;">
        <h3>Nội dung bài học</h3>
        <div style="background: white; padding: 2rem; border-radius: 8px;">
            <?= $lesson_content ?>
        </div>
    </div>
    
    <?php if ($has_materials): ?>
        <div style="margin: 2rem 0;">
            <h3>Tài liệu học tập</h3>
            <ul>
                <?php foreach ($danh_sách_tài_liệu as $tài_liệu): 
                    $material_filename = htmlspecialchars($tài_liệu['filename']);
                    $material_type = htmlspecialchars($tài_liệu['file_type']);
                ?>
                    <li><?= $material_filename ?> (<?= $material_type ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div style="margin: 2rem 0;">
        <h3>Danh sách bài học</h3>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($danh_sách_bài_học as $bài): 
                $bài_title = htmlspecialchars($bài['title']);
                $bài_id = $bài['id'];
                $is_current = ($bài_id === $lesson_id);
            ?>
                <li style="padding: 0.5rem; border-bottom: 1px solid #ddd;">
                    <?php if ($is_current): ?>
                        <strong><?= $bài_title ?> (Đang xem)</strong>
                    <?php else: ?>
                        <a href="index.php?controller=lesson&action=view&id=<?= $bài_id ?>"><?= $bài_title ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <a href="index.php?controller=student&action=course_progress&course_id=<?= $course_id ?>" class="btn btn-secondary">Quay lại</a>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
