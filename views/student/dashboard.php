<?php
$tiêu_đề = "Dashboard học viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';

// Tối ưu: Lưu trữ các giá trị thường dùng vào biến
$user_fullname = htmlspecialchars($_SESSION['fullname']);
$total_courses = count($danh_sách_khóa_học);
$has_courses = !empty($danh_sách_khóa_học);
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Dashboard học viên</h1>
            <p>Xin chào, <strong><?= $user_fullname ?></strong>!</p>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?= $total_courses ?></h3>
                    <p>Khóa học đã đăng ký</p>
                </div>
            </div>
            
            <h2>Khóa học của tôi</h2>
            
            <?php if ($has_courses): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tên khóa học</th>
                            <th>Giảng viên</th>
                            <th>Tiến độ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_khóa_học as $khóa_học): 
                            // Tối ưu: Lưu trữ các giá trị đã xử lý
                            $course_id = $khóa_học['course_id'];
                            $course_title = htmlspecialchars($khóa_học['title']);
                            $instructor_name = htmlspecialchars($khóa_học['tên_giảng_viên']);
                            $progress = intval($khóa_học['progress']);
                            $status = htmlspecialchars($khóa_học['status']);
                        ?>
                            <tr>
                                <td><?= $course_title ?></td>
                                <td><?= $instructor_name ?></td>
                                <td><?= $progress ?>%</td>
                                <td><?= $status ?></td>
                                <td>
                                    <a href="index.php?controller=student&action=course_progress&course_id=<?= $course_id ?>" 
                                       class="btn btn-small">Xem chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Bạn chưa đăng ký khóa học nào.</p>
                <a href="index.php?controller=course&action=index" class="btn btn-primary">Tìm khóa học</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
