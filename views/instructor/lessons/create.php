<?php
$tiêu_đề = "Tạo bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tạo bài học mới</h1>
            
            <form method="POST" action="index.php?controller=instructor&action=create_lesson&course_id=<?php echo $_GET['course_id']; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Tên bài học:</label>
                    <input type="text" id="title" name="title" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="content">Nội dung:</label>
                    <textarea id="content" name="content" rows="6" required class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="video_url">Video URL:</label>
                    <input type="url" id="video_url" name="video_url" placeholder="https://youtube.com/..." class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="image">🖼️ Ảnh minh họa:</label>
                    <div class="upload-area" id="lesson-image-area">
                        <input type="file" id="image" name="image" accept="image/*" class="form-control" onchange="previewLessonImage(this)">
                        <p style="margin-top: 0.5rem; opacity: 0.7; font-size: 0.9rem;">Chấp nhận: JPG, PNG, GIF. Tối đa 5MB</p>
                    </div>
                    <div id="lesson-image-preview" style="margin-top: 1rem;"></div>
                </div>
                
                <div class="form-group">
                    <label for="order">Thứ tự:</label>
                    <input type="number" id="order" name="order" min="1" value="1" class="form-control">
                </div>
                
                <button type="submit" class="btn btn-success">Tạo bài học</button>
                <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $_GET['course_id']; ?>" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<script>
function previewLessonImage(input) {
    const preview = document.getElementById('lesson-image-preview');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Kiểm tra loại file
        if (!file.type.match('image.*')) {
            alert('Vui lòng chọn file ảnh!');
            input.value = '';
            return;
        }
        
        // Kiểm tra kích thước (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ảnh không được vượt quá 5MB!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 300px; max-height: 200px; border-radius: 10px; border: 2px solid #00ffff;">';
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>