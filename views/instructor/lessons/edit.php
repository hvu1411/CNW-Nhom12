<h2>Sửa bài học: <?= htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8') ?></h2>

<?php if (!empty($errors)): ?>
<ul style="color:red;">
    <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<form method="post">
    <label>Tiêu đề:</label><br>
    <input type="text" name="title" 
           value="<?= htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label>Thứ tự:</label><br>
    <input type="number" name="order" value="<?= (int)$lesson['order'] ?>" min="1"><br><br>

    <label>Video URL:</label><br>
    <input type="text" name="video_url" 
           value="<?= htmlspecialchars($lesson['video_url'], ENT_QUOTES, 'UTF-8') ?>"><br><br>

    <label>Nội dung:</label><br>
    <textarea name="content" rows="6" cols="60"><?= htmlspecialchars($lesson['content'], ENT_QUOTES, 'UTF-8') ?></textarea><br><br>

    <button type="submit">Cập nhật</button>
</form>

<p>
    <a href="index.php?controller=lesson&action=manage&course_id=<?= (int)$course['id'] ?>">← Quay lại danh sách bài học</a>
</p>
