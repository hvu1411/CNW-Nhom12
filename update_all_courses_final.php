<?php
require_once 'config/Database.php';
$db = (new Database())->kếtNối();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Cập nhật toàn bộ khóa học</title></head><body>";
echo "<h2>🔄 Cập nhật TOÀN BỘ khóa học</h2>";

// Lấy TẤT CẢ khóa học
$all_courses = $db->query("SELECT id, title, description, image, category_id FROM courses ORDER BY id")->fetchAll();
echo "<p><strong>Tổng số khóa học trong database: " . count($all_courses) . "</strong></p>";

// Mapping danh mục
$category_map = [
    1 => "Lập trình Web",
    2 => "Lập trình Mobile", 
    3 => "Cơ sở dữ liệu",
    4 => "Khoa học dữ liệu",
    5 => "An ninh mạng"
];

// Mapping từ khóa -> ảnh và danh mục
$mappings = [
    ["keywords" => ["php", "mysql", "laravel"], "image" => "php.jpg", "category" => 1],
    ["keywords" => ["web", "html", "css", "javascript", "frontend"], "image" => "web.jpg", "category" => 1],
    ["keywords" => ["android", "kotlin"], "image" => "android.jpg", "category" => 2],
    ["keywords" => ["mobile", "app", "ios", "react native"], "image" => "mobile.jpg", "category" => 2],
    ["keywords" => ["database", "sql", "cơ sở dữ liệu"], "image" => "cosodulieu.jpg", "category" => 3],
    ["keywords" => ["data", "dữ liệu", "machine learning", "ai", "python"], "image" => "khoahocdulieu.jpg", "category" => 4],
    ["keywords" => ["security", "an ninh", "bảo mật", "network", "mạng"], "image" => "anninhmang.jpg", "category" => 5]
];

$updated_image = 0;
$updated_category = 0;

echo "<h3>Đang xử lý...</h3>";
echo "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Tên</th><th>Ảnh cũ</th><th>Ảnh mới</th><th>Danh mục cũ</th><th>Danh mục mới</th></tr>";

foreach ($all_courses as $course) {
    $search_text = strtolower($course['title'] . ' ' . $course['description']);
    $old_image = $course['image'];
    $old_category = $course['category_id'];
    
    $new_image = $old_image;
    $new_category = $old_category;
    
    // Tìm ảnh và danh mục phù hợp
    foreach ($mappings as $map) {
        foreach ($map['keywords'] as $keyword) {
            if (strpos($search_text, $keyword) !== false) {
                $new_image = $map['image'];
                $new_category = $map['category'];
                break 2;
            }
        }
    }
    
    // Nếu vẫn chưa có ảnh, gán mặc định
    if (empty($new_image)) {
        $new_image = 'web.jpg';
    }
    
    // Nếu vẫn chưa có danh mục, gán mặc định
    if (empty($new_category)) {
        $new_category = 1;
    }
    
    // Cập nhật vào database
    $need_update = false;
    if ($new_image != $old_image || $new_category != $old_category) {
        $stmt = $db->prepare("UPDATE courses SET image = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$new_image, $new_category, $course['id']]);
        $need_update = true;
        
        if ($new_image != $old_image) $updated_image++;
        if ($new_category != $old_category) $updated_category++;
    }
    
    $row_style = $need_update ? "background: #e8f5e9;" : "";
    echo "<tr style='$row_style'>";
    echo "<td>{$course['id']}</td>";
    echo "<td>" . htmlspecialchars(substr($course['title'], 0, 50)) . "</td>";
    echo "<td>" . ($old_image ?: '<span style="color:red;">Trống</span>') . "</td>";
    echo "<td><strong>$new_image</strong></td>";
    echo "<td>" . ($old_category ? ($category_map[$old_category] ?? "ID:$old_category") : '<span style="color:red;">Trống</span>') . "</td>";
    echo "<td><strong>" . ($category_map[$new_category] ?? "ID:$new_category") . "</strong></td>";
    echo "</tr>";
}

echo "</table>";

echo "<div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
echo "<h2>✅ HOÀN TẤT!</h2>";
echo "<p style='font-size: 1.2rem;'>📊 Tổng số khóa học: <strong>" . count($all_courses) . "</strong></p>";
echo "<p style='font-size: 1.2rem;'>🖼️ Đã cập nhật ảnh: <strong>$updated_image</strong> khóa học</p>";
echo "<p style='font-size: 1.2rem;'>📁 Đã cập nhật danh mục: <strong>$updated_category</strong> khóa học</p>";
echo "</div>";

// Kiểm tra lại danh mục
echo "<h3>📊 Thống kê theo danh mục:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'><th>Danh mục</th><th>Số khóa học</th></tr>";

foreach ($category_map as $cat_id => $cat_name) {
    $count = $db->query("SELECT COUNT(*) as total FROM courses WHERE category_id = $cat_id")->fetch();
    echo "<tr><td><strong>$cat_name</strong></td><td style='text-align: center;'>{$count['total']}</td></tr>";
}
echo "</table>";

echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='index.php' style='display: inline-block; padding: 12px 30px; background: #4CAF50; color: white; text-decoration: none; border-radius: 6px; margin: 5px; font-weight: bold;'>🏠 Về Trang Chủ</a>";
echo "<a href='index.php?controller=course&action=index' style='display: inline-block; padding: 12px 30px; background: #2196F3; color: white; text-decoration: none; border-radius: 6px; margin: 5px; font-weight: bold;'>📚 Xem Tất Cả Khóa Học</a>";
echo "</div>";

echo "</body></html>";
?>
