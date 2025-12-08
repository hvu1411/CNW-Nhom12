<h2>Tạo khóa học mới</h2>

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

    <label>Mô tả:</label><br>
    <textarea name="description" rows="5" cols="60"></textarea><br><br>

    <label>Danh mục (ID):</label><br>
    <input type="number" name="category_id" value="1"><br><br>

    <label>Giá:</label><br>
    <input type="number" step="0.01" name="price" value="0"><br><br>

    <label>Thời lượng (tuần):</label><br>
    <input type="number" name="duration_weeks" value="0"><br><br>

    <label>Cấp độ:</label><br>
    <select name="level">
        <option value="Beginner">Beginner</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advanced">Advanced</option>
    </select><br><br>

    <label>Ảnh (URL hoặc tên file):</label><br>
    <input type="text" name="image"><br><br>

    <button type="submit">Lưu</button>
</form>
