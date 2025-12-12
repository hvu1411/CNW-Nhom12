<h2>Khóa học của tôi</h2>

<a href="index.php?controller=course&action=create">+ Tạo khóa học mới</a>

<?php if (!empty($courses)): ?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Tiêu đề</th>
        <th>Giá</th>
        <th>Thời lượng (tuần)</th>
        <th>Cấp độ</th>
        <th>Thao tác</th>
    </tr>
    <?php foreach ($courses as $course): ?>
    <tr>
        <td><?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($course['price'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= (int)$course['duration_weeks'] ?></td>
        <td><?= htmlspecialchars($course['level'], ENT_QUOTES, 'UTF-8') ?></td>
        <td>
            <a href="index.php?controller=course&action=edit&id=<?= (int)$course['id'] ?>">Sửa</a> |
            <a href="index.php?controller=course&action=delete&id=<?= (int)$course['id'] ?>"
               onclick="return confirm('Bạn có chắc muốn xóa khóa học này?')">Xóa</a> |
            <a href="index.php?controller=lesson&action=manage&course_id=<?= (int)$course['id'] ?>">Bài học</a> |
            <a href="index.php?controller=enrollment&action=students&course_id=<?= (int)$course['id'] ?>">Học viên</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>Bạn chưa có khóa học nào.</p>
<?php endif; ?>
