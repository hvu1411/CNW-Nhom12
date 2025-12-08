<h2>Tải tài liệu cho bài học: <?= htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8') ?></h2>

<?php if (!empty($errors)): ?>
<ul style="color:red;">
    <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($success)): ?>
<p style="color:green;"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Chọn file (pdf, doc, ppt...):</label><br>
    <input type="file" name="file" required><br><br>
    <button type="submit">Upload</button>
</form>

<h3>Tài liệu đã tải lên</h3>
<?php if (!empty($materials)): ?>
<ul>
    <?php foreach ($materials as $m): ?>
        <li>
            <a href="<?= htmlspecialchars($m['file_path'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                <?= htmlspecialchars($m['filename'], ENT_QUOTES, 'UTF-8') ?>
            </a>
            (<?= htmlspecialchars($m['file_type'], ENT_QUOTES, 'UTF-8') ?>)
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p>Chưa có tài liệu nào.</p>
<?php endif; ?>

<p>
    <a href="index.php?controller=lesson&action=manage&course_id=<?= (int)$course['id'] ?>">
        ← Quay lại danh sách bài học
    </a>
</p>
