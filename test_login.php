<?php
/**
 * Test script để kiểm tra đăng nhập
 */

require_once 'config/Database.php';
require_once 'models/User.php';

// Kết nối database
$database = new Database();
$db = $database->kếtNối();

// Test thông tin
$test_username = 'hehe'; // Thay bằng username bạn muốn test
$test_password = '03012005'; // Thay bằng password bạn muốn test

echo "<h2>Test Đăng Nhập</h2>";

// Lấy thông tin user từ database
$query = "SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $test_username);
$stmt->bindParam(':email', $test_username);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo "<h3>✓ Tìm thấy user trong database</h3>";
    echo "<pre>";
    echo "ID: " . $row['id'] . "\n";
    echo "Username: " . $row['username'] . "\n";
    echo "Email: " . $row['email'] . "\n";
    echo "Role: " . $row['role'] . "\n";
    echo "Password Hash: " . $row['password'] . "\n";
    echo "</pre>";
    
    echo "<h3>Kiểm tra mật khẩu</h3>";
    echo "Mật khẩu test: <strong>" . htmlspecialchars($test_password) . "</strong><br>";
    
    // Test password_verify
    if (password_verify($test_password, $row['password'])) {
        echo "<div style='color: green; font-weight: bold;'>✓ Mật khẩu ĐÚNG!</div>";
        
        // Test với User model
        $userModel = new User($db);
        $userModel->username = $test_username;
        $userModel->password = $test_password;
        
        if ($userModel->đăngNhập()) {
            echo "<div style='color: green;'>✓ Đăng nhập qua User model THÀNH CÔNG!</div>";
        } else {
            echo "<div style='color: red;'>✗ Đăng nhập qua User model THẤT BẠI!</div>";
        }
    } else {
        echo "<div style='color: red; font-weight: bold;'>✗ Mật khẩu SAI!</div>";
        echo "<p>Gợi ý: Mật khẩu trong database có thể không được hash đúng cách.</p>";
        
        // Tạo hash mới để so sánh
        $new_hash = password_hash($test_password, PASSWORD_BCRYPT);
        echo "<p>Hash mới tạo: <code>" . $new_hash . "</code></p>";
        echo "<p>Hash trong DB: <code>" . $row['password'] . "</code></p>";
    }
} else {
    echo "<h3 style='color: red;'>✗ KHÔNG tìm thấy user với username: " . htmlspecialchars($test_username) . "</h3>";
    echo "<p>Vui lòng kiểm tra lại username hoặc đăng ký tài khoản mới.</p>";
}

echo "<hr>";
echo "<h3>Danh sách tất cả users:</h3>";
$query_all = "SELECT id, username, email, role, created_at FROM users";
$stmt_all = $db->prepare($query_all);
$stmt_all->execute();
$users = $stmt_all->fetchAll(PDO::FETCH_ASSOC);

if ($users) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created At</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td>" . $user['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Chưa có user nào trong database.</p>";
}
?>
