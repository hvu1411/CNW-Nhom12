<?php
/**
 * Script reset mật khẩu cho user
 */

require_once 'config/Database.php';

// Kết nối database
$database = new Database();
$db = $database->kếtNối();

echo "<h2>Reset Mật Khẩu User</h2>";

// Lấy danh sách users
$query = "SELECT id, username, email, role FROM users ORDER BY id";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['new_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate
    if (empty($new_password)) {
        echo "<div style='color: red; padding: 10px; background: #ffeeee; margin: 10px 0;'>❌ Vui lòng nhập mật khẩu mới!</div>";
    } elseif ($new_password !== $confirm_password) {
        echo "<div style='color: red; padding: 10px; background: #ffeeee; margin: 10px 0;'>❌ Mật khẩu xác nhận không khớp!</div>";
    } else {
        // Hash mật khẩu mới
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        // Cập nhật vào database
        $update_query = "UPDATE users SET password = :password WHERE id = :id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':password', $hashed_password);
        $update_stmt->bindParam(':id', $user_id);
        
        if ($update_stmt->execute()) {
            // Lấy thông tin user vừa update
            $user_query = "SELECT username FROM users WHERE id = :id";
            $user_stmt = $db->prepare($user_query);
            $user_stmt->bindParam(':id', $user_id);
            $user_stmt->execute();
            $user_info = $user_stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "<div style='color: green; padding: 15px; background: #eeffee; margin: 10px 0; border: 2px solid green;'>";
            echo "✅ <strong>Reset mật khẩu THÀNH CÔNG!</strong><br><br>";
            echo "👤 Username: <strong>" . htmlspecialchars($user_info['username']) . "</strong><br>";
            echo "🔑 Mật khẩu mới: <strong>" . htmlspecialchars($new_password) . "</strong><br><br>";
            echo "<a href='index.php?controller=auth&action=login' style='display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Đi tới trang đăng nhập</a>";
            echo "</div>";
        } else {
            echo "<div style='color: red; padding: 10px; background: #ffeeee; margin: 10px 0;'>❌ Lỗi khi cập nhật mật khẩu!</div>";
        }
    }
}

// Hiển thị form
if ($users) {
    echo "<div style='background: #f9f9f9; padding: 20px; border: 1px solid #ddd; max-width: 500px;'>";
    echo "<form method='POST'>";
    
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label style='display: block; margin-bottom: 5px; font-weight: bold;'>Chọn User:</label>";
    echo "<select name='user_id' required style='width: 100%; padding: 8px; font-size: 14px;'>";
    foreach ($users as $user) {
        $role_text = '';
        switch($user['role']) {
            case 0: $role_text = 'Học viên'; break;
            case 1: $role_text = 'Giảng viên'; break;
            case 2: $role_text = 'Admin'; break;
        }
        echo "<option value='" . $user['id'] . "'>" . 
             htmlspecialchars($user['username']) . " (" . $role_text . ")</option>";
    }
    echo "</select>";
    echo "</div>";
    
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label style='display: block; margin-bottom: 5px; font-weight: bold;'>Mật khẩu mới:</label>";
    echo "<input type='text' name='new_password' required placeholder='Nhập mật khẩu mới' style='width: 100%; padding: 8px; font-size: 14px;'>";
    echo "</div>";
    
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label style='display: block; margin-bottom: 5px; font-weight: bold;'>Xác nhận mật khẩu:</label>";
    echo "<input type='text' name='confirm_password' required placeholder='Nhập lại mật khẩu' style='width: 100%; padding: 8px; font-size: 14px;'>";
    echo "</div>";
    
    echo "<button type='submit' style='background: #2196F3; color: white; padding: 10px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;'>🔄 Reset Mật Khẩu</button>";
    
    echo "</form>";
    echo "</div>";
    
    echo "<hr style='margin: 30px 0;'>";
    
    echo "<h3>Danh Sách Users Hiện Tại:</h3>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";
    foreach ($users as $user) {
        $role_text = '';
        switch($user['role']) {
            case 0: $role_text = 'Học viên'; break;
            case 1: $role_text = 'Giảng viên'; break;
            case 2: $role_text = 'Admin'; break;
        }
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td><strong>" . htmlspecialchars($user['username']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td>" . $role_text . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} else {
    echo "<p style='color: red;'>Không có user nào trong database.</p>";
}

echo "<hr>";
echo "<div style='background: #ffffcc; padding: 15px; margin: 20px 0;'>";
echo "<h3>⚠️ Lưu Ý:</h3>";
echo "<ul>";
echo "<li>Mật khẩu mới sẽ được mã hóa tự động trước khi lưu vào database</li>";
echo "<li>Hãy nhớ mật khẩu mới vì không thể xem lại sau khi đã mã hóa</li>";
echo "<li>Nên dùng mật khẩu dễ nhớ khi đang phát triển (ví dụ: 123456)</li>";
echo "</ul>";
echo "</div>";
?>
