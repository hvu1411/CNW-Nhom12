<?php
$tiêu_đề = "Khóa học của Giảng viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <div class="content-header">
                <div>
                    <h1>📚 Khóa học của: <?php echo htmlspecialchars($giảng_viên['fullname']); ?></h1>
                    <p style="opacity: 0.7;">@<?php echo htmlspecialchars($giảng_viên['username']); ?> - <?php echo htmlspecialchars($giảng_viên['email']); ?></p>
                </div>
                <a href="index.php?controller=admin&action=list_instructors" class="btn btn-secondary">← Quay lại</a>
            </div>
            
            <?php if (!empty($danh_sách_khóa_học)): ?>
                <div class="stats-row">
                    <div class="stat-item">
                        <strong><?php echo count($danh_sách_khóa_học); ?></strong>
                        <span>Khóa học</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo $tổng_học_viên ?? 0; ?></strong>
                        <span>Học viên đăng ký</span>
                    </div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên khóa học</th>
                            <th>Danh mục</th>
                            <th>Trình độ</th>
                            <th>Giá</th>
                            <th>Học viên</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_khóa_học as $kh): ?>
                            <?php $status = $kh['status'] ?? 1; ?>
                            <tr>
                                <td><?php echo $kh['id']; ?></td>
                                <td>
                                    <?php if (!empty($kh['image'])): ?>
                                        <img src="assets/images/<?php echo htmlspecialchars($kh['image']); ?>" 
                                             alt="Course" style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 5px; display: flex; align-items: center; justify-content: center;">📖</div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($kh['title']); ?></td>
                                <td><?php echo htmlspecialchars($kh['tên_danh_mục'] ?? 'Chưa phân loại'); ?></td>
                                <td>
                                    <span class="level-badge level-<?php echo strtolower($kh['level']); ?>">
                                        <?php echo htmlspecialchars($kh['level']); ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($kh['price'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo $kh['số_học_viên'] ?? 0; ?></td>
                                <td>
                                    <?php if ($status == 1): ?>
                                        <span class="status-active">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="status-inactive">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="index.php?controller=course&action=detail&id=<?php echo $kh['id']; ?>" 
                                       class="btn btn-small" title="Xem chi tiết" target="_blank">👁️</a>
                                    <a href="index.php?controller=admin&action=delete_course&id=<?php echo $kh['id']; ?>&instructor_id=<?php echo $giảng_viên['id']; ?>" 
                                       onclick="return xácNhậnXóa('Bạn có chắc muốn xóa khóa học này?')" 
                                       class="btn btn-small btn-danger" title="Xóa">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>Giảng viên này chưa có khóa học nào.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}
.stats-row {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}
.stat-item {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem 2rem;
    border-radius: 10px;
    text-align: center;
}
.stat-item strong {
    display: block;
    font-size: 2rem;
    color: #00ffff;
}
.stat-item span {
    opacity: 0.7;
}
.level-badge {
    padding: 0.2rem 0.6rem;
    border-radius: 5px;
    font-size: 0.8rem;
}
.level-beginner { background: #4CAF50; color: #fff; }
.level-intermediate { background: #FF9800; color: #fff; }
.level-advanced { background: #F44336; color: #fff; }
.status-active { color: #4CAF50; }
.status-inactive { color: #F44336; }
.empty-state {
    text-align: center;
    padding: 3rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>
