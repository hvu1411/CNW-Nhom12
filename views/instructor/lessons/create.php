<h2>Thêm bài học mới cho khóa: <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></h2>

<?php if (!empty($errors)): ?>
<ul style="color:red;">
    <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<form method="post">
    <label>Tiêu đề:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Thứ tự:</label><br>
    <input type="number" name="order" value="1" min="1"><br><br>

    <label>Video URL:</label><br>
    <input type="text" name="video_url"><br><br>

    <label>Nội dung:</label><br>
    <textarea name="content" rows="6" cols="60"></textarea><br><br>

    <button type="submit">Lưu</button>
</form>

<p>
    <a href="index.php?controller=lesson&action=manage&course_id=<?= (int)$course['id'] ?>">← Quay lại danh sách bài học</a>
</p>
