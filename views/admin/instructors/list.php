<?php
$tiêu_đề = "Quản lý Giảng viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <div class="content-header">
                <h1> Quản lý Giảng viên</h1>
                <a href="index.php?controller=admin&action=create_instructor" class="btn btn-primary">+ Thêm giảng viên</a>
            </div>
            
            <?php if (!empty($danh_sách_giảng_viên)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th>Số khóa học</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_giảng_viên as $gv): ?>
                            <tr>
                                <td><?php echo $gv['id']; ?></td>
                                <td>
                                    <?php if (!empty($gv['avatar'])): ?>
                                        <img src="assets/uploads/avatars/<?php echo htmlspecialchars($gv['avatar']); ?>" 
                                             alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    <?php else: ?>
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #ff00ff, #00ffff); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold;">
                                            <?php echo strtoupper(substr($gv['fullname'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($gv['username']); ?></td>
                                <td><?php echo htmlspecialchars($gv['email']); ?></td>
                                <td><?php echo htmlspecialchars($gv['fullname']); ?></td>
                                <td>
                                    <span class="badge"><?php echo $gv['số_khóa_học'] ?? 0; ?> khóa học</span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($gv['created_at'])); ?></td>
                                <td>
                                    <a href="index.php?controller=admin&action=view_instructor_courses&id=<?php echo $gv['id']; ?>" 
                                       class="btn btn-small btn-secondary" title="Xem khóa học">📚</a>
                                    <a href="index.php?controller=admin&action=edit_instructor&id=<?php echo $gv['id']; ?>" 
                                       class="btn btn-small" title="Sửa">✏️</a>
                                    <a href="index.php?controller=admin&action=delete_instructor&id=<?php echo $gv['id']; ?>" 
                                       onclick="return xácNhậnXóa('Bạn có chắc muốn xóa giảng viên này? Tất cả khóa học của giảng viên cũng sẽ bị xóa!')" 
                                       class="btn btn-small btn-danger" title="Xóa">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>Chưa có giảng viên nào.</p>
                    <a href="index.php?controller=admin&action=create_instructor" class="btn btn-primary">Thêm giảng viên đầu tiên</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
.badge {
    background: linear-gradient(135deg, #ff00ff, #00ffff);
    color: #fff;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
}
.empty-state {
    text-align: center;
    padding: 3rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>
