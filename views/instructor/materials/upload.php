<?php
/**
 * Trang Upload Tài Liệu - Giảng viên
 * Chức năng: Upload file PDF, DOC, DOCX, PPT, PPTX cho bài học
 */

$tiêu_đề = "Tải lên tài liệu";
require_once 'views/layouts/header.php';

// Lấy lesson_id từ URL
$lesson_id = $_GET['lesson_id'] ?? '';
$course_id = $khóa_học['id'] ?? '';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>📚 Tải lên tài liệu học tập</h1>
            
            <!-- Form Upload -->
            <form id="material-upload-form" 
                  method="POST" 
                  action="index.php?controller=instructor&action=upload_material&lesson_id=<?= $lesson_id ?>" 
                  enctype="multipart/form-data"
                  class="upload-form">
                
                <!-- Tiêu đề -->
                <div class="form-group">
                    <label for="title">Tiêu đề tài liệu <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" 
                           placeholder="VD: Slide bài giảng Chương 1" required>
                </div>
                
                <!-- Mô tả -->
                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea id="description" name="description" class="form-control" rows="3" 
                              placeholder="Mô tả ngắn về tài liệu (không bắt buộc)"></textarea>
                </div>
                
                <!-- Vùng Upload -->
                <div class="form-group">
                    <label>Chọn file <span class="required">*</span></label>
                    <div class="upload-area" id="material-upload-area">
                        <div class="upload-icon">📂</div>
                        <p class="upload-text">Kéo thả file hoặc <strong>click để chọn</strong></p>
                        <p class="upload-hint">PDF, DOC, DOCX, PPT, PPTX • Tối đa 10MB</p>
                        <input type="file" id="material-input" name="material_file" 
                               accept=".pdf,.doc,.docx,.ppt,.pptx" 
                               onchange="previewMaterial(this)" hidden required>
                    </div>
                    <div id="file-info" class="file-info"></div>
                </div>
                
                <!-- Progress Bar -->
                <div class="progress-bar" id="material-progress">
                    <div class="progress-fill">0%</div>
                </div>
                
                <!-- Nút Bấm -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">📤 Tải lên</button>
                    <a href="index.php?controller=instructor&action=manage_course&id=<?= $course_id ?>" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
            
            <!-- Danh sách tài liệu đã upload -->
            <?php if (!empty($tài_liệu_hiện_có)): ?>
            <div class="material-section">
                <h2>📁 Tài liệu đã tải lên</h2>
                <ul class="material-list">
                    <?php foreach ($tài_liệu_hiện_có as $tl): ?>
                    <li class="material-item">
                        <div class="material-info">
                            <span class="material-icon"><?= getFileIconPHP($tl['filename']) ?></span>
                            <div>
                                <div class="material-name"><?= htmlspecialchars($tl['filename']) ?></div>
                                <div class="material-meta"><?= date('d/m/Y H:i', strtotime($tl['uploaded_at'])) ?></div>
                            </div>
                        </div>
                        <div class="material-actions">
                            <a href="assets/uploads/materials/<?= htmlspecialchars($tl['file_path']) ?>" class="btn btn-small" download>⬇️ Tải</a>
                            <a href="index.php?controller=instructor&action=delete_material&id=<?= $tl['id'] ?>" 
                               class="btn btn-small btn-danger" 
                               onclick="return confirm('Xóa tài liệu này?')">🗑️</a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Hàm lấy icon theo loại file
function getFileIconPHP($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icons = [
        'pdf' => '📄',
        'doc' => '📝', 'docx' => '📝',
        'ppt' => '📊', 'pptx' => '📊'
    ];
    return $icons[$ext] ?? '📎';
}
?>

<?php require_once 'views/layouts/footer.php'; ?>
