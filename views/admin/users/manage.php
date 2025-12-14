<?php
$tiêu_đề = "Quản lý người dùng - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>

        <div class="content">
            <h1>Quản lý người dùng</h1>

            <?php
            // Cache session access and prepare mappings to reduce per-row work
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $currentUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
            $roleLabels = [0 => 'Học viên', 1 => 'Giảng viên', 2 => 'Quản trị viên'];

            if (!empty($danh_sách_người_dùng)):
            ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Localize commonly used functions to variables for tiny speed gains
                        $h = 'htmlspecialchars';
                        foreach ($danh_sách_người_dùng as $người_dùng):
                            $id = isset($người_dùng['id']) ? (int)$người_dùng['id'] : 0;
                            $username = $h($người_dùng['username'] ?? '', ENT_QUOTES, 'UTF-8');
                            $email = $h($người_dùng['email'] ?? '', ENT_QUOTES, 'UTF-8');
                            $fullname = $h($người_dùng['fullname'] ?? '', ENT_QUOTES, 'UTF-8');
                            $role = isset($người_dùng['role']) ? (int)$người_dùng['role'] : null;

                            // Fast date formatting without strtotime/DateTime for standard 'Y-m-d H:i:s' strings
                            $createdRaw = $người_dùng['created_at'] ?? '';
                            $createdDisplay = '';
                            if (strlen($createdRaw) >= 10) {
                                $datePart = substr($createdRaw, 0, 10); // YYYY-MM-DD
                                $parts = explode('-', $datePart);
                                if (count($parts) === 3) {
                                    $createdDisplay = $parts[2] . '/' . $parts[1] . '/' . $parts[0];
                                }
                            }

                            $roleLabel = $role !== null && isset($roleLabels[$role]) ? $roleLabels[$role] : 'N/A';

                            // Render row in one echo to minimize PHP/context switches
                            echo '<tr>' .
                                    '<td>' . $id . '</td>' .
                                    '<td>' . $username . '</td>' .
                                    '<td>' . $email . '</td>' .
                                    '<td>' . $fullname . '</td>' .
                                    '<td>' . $roleLabel . '</td>' .
                                    '<td>' . $createdDisplay . '</td>' .
                                    '<td>' .
                                        (($id !== $currentUserId)
                                            ? '<a href="index.php?controller=admin&action=delete_user&id=' . $id . '" onclick="return xácNhậnXóa(\'Bạn có chắc muốn xóa người dùng này?\')" class="btn btn-small btn-danger">Xóa</a>'
                                            : '') .
                                    '</td>' .
                                 '</tr>';
                        endforeach;
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Không có người dùng nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
