<?php
$tiêu_đề = "Tải lên tài liệu - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tải lên tài liệu học tập</h1>
            
            <form method="POST" action="index.php?controller=instructor&action=upload_material&lesson_id=<?php echo $_GET['lesson_id']; ?>">
                <div class="form-group">
                    <label for="filename">Tên file:</label>
                    <input type="text" id="filename" name="filename" required class="form-control" placeholder="example.pdf">
                </div>
                
                <div class="form-group">
                    <label for="file_path">Đường dẫn file:</label>
                    <input type="text" id="file_path" name="file_path" required class="form-control" placeholder="materials/lesson1/example.pdf">
                </div>
                
                <div class="form-group">
                    <label for="file_type">Loại file:</label>
                    <select id="file_type" name="file_type" class="form-control">
                        <option value="pdf">PDF</option>
                        <option value="doc">DOC</option>
                        <option value="ppt">PPT</option>
                        <option value="zip">ZIP</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-success">Tải lên</button>
                <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $khóa_học['id'] ?? ''; ?>" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
