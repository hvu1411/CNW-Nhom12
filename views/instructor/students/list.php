<h2>Học viên đăng ký khóa: <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></h2>

<?php if (!empty($students)): ?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Ngày đăng ký</th>
        <th>Trạng thái</th>
        <th>Tiến độ (%)</th>
        <th>Cập nhật</th>
    </tr>
    <?php foreach ($students as $s): ?>
    <tr>
        <td><?= htmlspecialchars($s['fullname'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($s['enrolled_date'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($s['status'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= (int)$s['progress'] ?></td>
        <td>
            <!-- Form cập nhật trạng thái và tiến độ -->
            <form method="post" action="index.php?controller=enrollment&action=updateProgress">
                <input type="hidden" name="course_id" value="<?= (int)$course['id'] ?>">
                <input type="hidden" name="enrollment_id" value="<?= (int)$s['id'] ?>">

                <select name="status">
                    <option value="active"    <?= $s['status'] === 'active' ? 'selected' : '' ?>>active</option>
                    <option value="completed" <?= $s['status'] === 'completed' ? 'selected' : '' ?>>completed</option>
                    <option value="dropped"   <?= $s['status'] === 'dropped' ? 'selected' : '' ?>>dropped</option>
                </select>

                <input type="number" name="progress"
                       value="<?= (int)$s['progress'] ?>" min="0" max="100" style="width:60px;">

                <button type="submit">Lưu</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>Chưa có học viên đăng ký khóa này.</p>
<?php endif; ?>

<p>
    <a href="index.php?controller=course&action=list">← Quay lại danh sách khóa học</a>
</p>
