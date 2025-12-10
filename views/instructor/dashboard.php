<h2>Trang giảng viên</h2>

<p>Xin chào, <?= htmlspecialchars($_SESSION['fullname'] ?? 'Giảng viên', ENT_QUOTES, 'UTF-8') ?></p>

<ul>
    <li>
        <a href="index.php?controller=course&action=list">
            Quản lý khóa học của tôi
        </a>
    </li>
</ul>

<p>Vào mục “Quản lý khóa học của tôi” để tạo, chỉnh sửa khóa học, quản lý bài học, tài liệu và học viên.</p>
