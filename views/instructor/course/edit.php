<h2>Sửa khóa học: <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></h2>

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
           value="<?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label>Mô tả:</label><br>
    <textarea name="description" rows="5" cols="60"><?= htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8') ?></textarea><br><br>

    <label>Danh mục (ID):</label><br>
    <input type="number" name="category_id" value="<?= (int)$course['category_id'] ?>"><br><br>

    <label>Giá:</label><br>
    <input type="number" step="0.01" name="price" 
           value="<?= htmlspecialchars($course['price'], ENT_QUOTES, 'UTF-8') ?>"><br><br>

    <label>Thời lượng (tuần):</label><br>
    <input type="number" name="duration_weeks" value="<?= (int)$course['duration_weeks'] ?>"><br><br>

    <label>Cấp độ:</label><br>
    <select name="level">
        <option value="Beginner"    <?= $course['level'] === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
        <option value="Intermediate"<?= $course['level'] === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
        <option value="Advanced"   <?= $course['level'] === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
    </select><br><br>

    <label>Ảnh (URL hoặc tên file):</label><br>
    <input type="text" name="image" 
           value="<?= htmlspecialchars($course['image'], ENT_QUOTES, 'UTF-8') ?>"><br><br>

    <button type="submit">Cập nhật</button>
</form>
