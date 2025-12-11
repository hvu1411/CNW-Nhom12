<?php
$tiêu_đề = "Bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<style>
    .lesson-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .lesson-title {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 20px #00ffff;
    }
    
    .lesson-course {
        color: #ff00ff;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }
    
    .lesson-course a {
        color: #ff00ff;
        text-decoration: none;
    }
    
    .lesson-course a:hover {
        text-shadow: 0 0 10px #ff00ff;
    }
    
    .video-section {
        background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 100%);
        border: 2px solid #ff00ff;
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 0 30px rgba(255, 0, 255, 0.3);
    }
    
    .video-section h3 {
        color: #00ffff;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .video-section h3::before {
        content: '🎬';
    }
    
    .video-link {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%);
        color: #ffffff !important;
        padding: 16px 28px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(255, 0, 0, 0.4);
    }
    
    .video-link:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 30px rgba(255, 0, 0, 0.6);
        text-shadow: 0 0 10px #fff;
    }
    
    .video-link::before {
        content: '▶';
        font-size: 1.2rem;
    }
    
    .video-url-text {
        display: block;
        margin-top: 1rem;
        color: #8866aa;
        font-size: 0.9rem;
        word-break: break-all;
    }
    
    .content-section {
        background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 100%);
        border: 1px solid #ff00ff40;
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem 0;
    }
    
    .content-section h3 {
        color: #00ffff;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }
    
    .content-box {
        background: rgba(255, 255, 255, 0.95);
        color: #1a0a2e;
        padding: 2rem;
        border-radius: 12px;
        font-size: 1.05rem;
        line-height: 1.8;
    }
    
    .materials-section {
        background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 100%);
        border: 1px solid #00ffff40;
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem 0;
    }
    
    .materials-section h3 {
        color: #00ffff;
        margin-bottom: 1.5rem;
    }
    
    .materials-list {
        list-style: none;
        padding: 0;
    }
    
    .materials-list li {
        background: rgba(0, 255, 255, 0.1);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        border: 1px solid rgba(0, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .materials-list li:hover {
        background: rgba(0, 255, 255, 0.2);
        border-color: #00ffff;
        box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
    }
    
    .materials-list li::before {
        display: none;
    }
    
    .material-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }
    
    .material-info::before {
        content: '📄';
        font-size: 1.5rem;
    }
    
    .material-name {
        color: #00ffff;
        font-weight: 600;
        font-size: 1.05rem;
    }
    
    .material-type {
        color: #8866aa;
        font-size: 0.9rem;
    }
    
    .material-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-view-material,
    .btn-download-material {
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-view-material {
        background: linear-gradient(135deg, #00ffff 0%, #0099cc 100%);
        color: #0d0221 !important;
    }
    
    .btn-view-material:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 255, 255, 0.5);
    }
    
    .btn-download-material {
        background: linear-gradient(135deg, #ff00ff 0%, #cc00cc 100%);
        color: #ffffff !important;
    }
    
    .btn-download-material:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 0, 255, 0.5);
    }
    
    .lessons-section {
        background: linear-gradient(135deg, #1a0a2e 0%, #2d1b4e 100%);
        border: 1px solid #ff00ff40;
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem 0;
    }
    
    .lessons-section h3 {
        color: #00ffff;
        margin-bottom: 1.5rem;
    }
    
    .lessons-list {
        list-style: none;
        padding: 0;
    }
    
    .lessons-list li {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #ff00ff30;
        transition: all 0.3s ease;
    }
    
    .lessons-list li:last-child {
        border-bottom: none;
    }
    
    .lessons-list li:hover {
        background: rgba(255, 0, 255, 0.1);
    }
    
    .lessons-list li a {
        color: #00ffff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .lessons-list li a::before {
        content: '📚';
    }
    
    .lessons-list li a:hover {
        text-shadow: 0 0 10px #00ffff;
    }
    
    .lessons-list .current-lesson {
        color: #ff00ff;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .lessons-list .current-lesson::before {
        content: '👉';
    }
    
    .back-btn {
        margin-top: 2rem;
    }
    
    /* Ảnh minh họa bài học */
    .lesson-image-section {
        margin: 2rem 0;
        text-align: center;
    }
    
    .lesson-image {
        max-width: 100%;
        max-height: 500px;
        border-radius: 16px;
        border: 3px solid #00ffff;
        box-shadow: 0 0 30px rgba(0, 255, 255, 0.3);
        transition: transform 0.3s ease;
    }
    
    .lesson-image:hover {
        transform: scale(1.02);
        box-shadow: 0 0 50px rgba(0, 255, 255, 0.5);
    }
    
    .lesson-image-caption {
        color: #8866aa;
        font-style: italic;
        margin-top: 1rem;
        font-size: 0.95rem;
    }
</style>

<div class="lesson-container">
    <h1 class="lesson-title"><?php echo htmlspecialchars($bài_học['title']); ?></h1>
    <p class="lesson-course">
        <strong>Khóa học:</strong> 
        <a href="index.php?controller=course&action=detail&id=<?php echo $khóa_học['id']; ?>">
            <?php echo htmlspecialchars($khóa_học['title']); ?>
        </a>
    </p>
    
    <?php if (!empty($bài_học['video_url'])): ?>
        <div class="video-section">
            <h3>Video bài học</h3>
            <a href="<?php echo htmlspecialchars($bài_học['video_url']); ?>" target="_blank" class="video-link">
                Xem Video trên YouTube
            </a>
            <span class="video-url-text">
                🔗 <?php echo htmlspecialchars($bài_học['video_url']); ?>
            </span>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($bài_học['image'])): ?>
        <div class="lesson-image-section">
            <img src="assets/uploads/lessons/<?php echo htmlspecialchars($bài_học['image']); ?>" 
                 alt="Ảnh minh họa - <?php echo htmlspecialchars($bài_học['title']); ?>" 
                 class="lesson-image">
            <p class="lesson-image-caption">🖼️ Ảnh minh họa bài học</p>
        </div>
    <?php endif; ?>
    
    <div class="content-section">
        <h3>📖 Nội dung bài học</h3>
        <div class="content-box">
            <?php echo nl2br(htmlspecialchars($bài_học['content'])); ?>
        </div>
    </div>
    
    <?php if (!empty($danh_sách_tài_liệu)): ?>
        <div class="materials-section">
            <h3>📚 Tài liệu học tập</h3>
            <ul class="materials-list">
                <?php foreach ($danh_sách_tài_liệu as $tài_liệu): ?>
                    <?php 
                    // Tạo đường dẫn đầy đủ đến file
                    $file_url = 'assets/uploads/materials/' . $tài_liệu['file_path'];
                    ?>
                    <li>
                        <div class="material-info">
                            <span class="material-name"><?php echo htmlspecialchars($tài_liệu['filename']); ?></span>
                            <span class="material-type">(<?php echo htmlspecialchars($tài_liệu['file_type']); ?>)</span>
                        </div>
                        <div class="material-actions">
                            <a href="<?php echo htmlspecialchars($file_url); ?>" 
                               target="_blank" 
                               class="btn-view-material">
                                👁️ Xem
                            </a>
                            <a href="<?php echo htmlspecialchars($file_url); ?>" 
                               download="<?php echo htmlspecialchars($tài_liệu['filename']); ?>" 
                               class="btn-download-material">
                                ⬇️ Tải xuống
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="lessons-section">
        <h3>📋 Danh sách bài học</h3>
        <ul class="lessons-list">
            <?php foreach ($danh_sách_bài_học as $bài): ?>
                <li>
                    <?php if ($bài['id'] == $bài_học['id']): ?>
                        <span class="current-lesson"><?php echo htmlspecialchars($bài['title']); ?> (Đang xem)</span>
                    <?php else: ?>
                        <a href="index.php?controller=lesson&action=view&id=<?php echo $bài['id']; ?>">
                            <?php echo htmlspecialchars($bài['title']); ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="back-btn">
        <a href="index.php?controller=student&action=course_progress&course_id=<?php echo $khóa_học['id']; ?>" class="btn btn-secondary">← Quay lại</a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
