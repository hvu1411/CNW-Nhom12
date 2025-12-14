<h2>Bài học trong khóa: <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></h2>

<a href="index.php?controller=lesson&action=create&course_id=<?= (int)$course['id'] ?>">+ Thêm bài học</a>

<?php if (!empty($lessons)): ?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Thứ tự</th>
        <th>Tiêu đề</th>
        <th>Video</th>
        <th>Thao tác</th>
    </tr>
    <?php foreach ($lessons as $l): ?>
    <tr>
        <td><?= (int)$l['order'] ?></td>
        <td><?= htmlspecialchars($l['title'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($l['video_url'], ENT_QUOTES, 'UTF-8') ?></td>
        <td>
            <a href="index.php?controller=lesson&action=edit&id=<?= (int)$l['id'] ?>">Sửa</a> |
            <a href="index.php?controller=lesson&action=delete&id=<?= (int)$l['id'] ?>"
               onclick="return confirm('Xóa bài học này?')">Xóa</a> |
            <a href="index.php?controller=lesson&action=uploadMaterial&lesson_id=<?= (int)$l['id'] ?>">Tài liệu</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>Chưa có bài học nào.</p>
<?php endif; ?>

<p>
    <a href="index.php?controller=course&action=list">← Quay lại khóa học</a>
</p>
