<?php
$tiêu_đề = "Chỉnh sửa bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Chỉnh sửa bài học</h1>
            
            <?php if ($bài_học): ?>
                <form method="POST" action="index.php?controller=instructor&action=edit_lesson&id=<?php echo $bài_học['id']; ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Tên bài học:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($bài_học['title']); ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Nội dung:</label>
                        <textarea id="content" name="content" rows="6" required class="form-control"><?php echo htmlspecialchars($bài_học['content']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="video_url">Video URL:</label>
                        <input type="url" id="video_url" name="video_url" value="<?php echo htmlspecialchars($bài_học['video_url']); ?>" placeholder="https://youtube.com/..." class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">🖼️ Ảnh minh họa:</label>
                        <?php if (!empty($bài_học['image'])): ?>
                            <div class="current-image" style="margin-bottom: 1rem;">
                                <p style="opacity: 0.7;">Ảnh hiện tại:</p>
                                <img src="assets/uploads/lessons/<?php echo htmlspecialchars($bài_học['image']); ?>" 
                                     style="max-width: 300px; max-height: 200px; border-radius: 10px; border: 2px solid #00ffff;">
                            </div>
                        <?php endif; ?>
                        <div class="upload-area">
                            <input type="file" id="image" name="image" accept="image/*" class="form-control" onchange="previewLessonImage(this)">
                            <p style="margin-top: 0.5rem; opacity: 0.7; font-size: 0.9rem;">Chọn ảnh mới để thay thế. Chấp nhận: JPG, PNG, GIF. Tối đa 5MB</p>
                        </div>
                        <div id="lesson-image-preview" style="margin-top: 1rem;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="order">Thứ tự:</label>
                        <input type="number" id="order" name="order" min="1" value="<?php echo $bài_học['order']; ?>" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                    <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $bài_học['course_id']; ?>" class="btn btn-secondary">Hủy</a>
                </form>
            <?php else: ?>
                <p>Không tìm thấy bài học.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function previewLessonImage(input) {
    const preview = document.getElementById('lesson-image-preview');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (!file.type.match('image.*')) {
            alert('Vui lòng chọn file ảnh!');
            input.value = '';
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            alert('Ảnh không được vượt quá 5MB!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<p style="opacity: 0.7;">Ảnh mới:</p><img src="' + e.target.result + '" style="max-width: 300px; max-height: 200px; border-radius: 10px; border: 2px solid #ff00ff;">';
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>