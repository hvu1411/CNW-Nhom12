<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tiêu_đề ?? 'Hệ thống Quản lý Khóa học Online'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="bg-particles">
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
        <span class="particle"></span>
    </div>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">Khóa học Online</a>
                </div>
                <nav class="nav">
                    <ul>
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="index.php?controller=course&action=index">Khóa học</a></li>
                        
                        <?php if (isset($_SESSION['đã_đăng_nhập']) && $_SESSION['đã_đăng_nhập']): ?>
                            <?php if ($_SESSION['role'] == 0): // Học viên ?>
                                <li><a href="index.php?controller=student&action=dashboard">Dashboard</a></li>
                                <li><a href="index.php?controller=student&action=my_courses">Khóa học của tôi</a></li>
                            <?php elseif ($_SESSION['role'] == 1): // Giảng viên ?>
                                <li><a href="index.php?controller=instructor&action=dashboard">Dashboard</a></li>
                                <li><a href="index.php?controller=instructor&action=my_courses">Khóa học của tôi</a></li>
                            <?php elseif ($_SESSION['role'] == 2): // Admin ?>
                                <li><a href="index.php?controller=admin&action=dashboard">Quản trị</a></li>
                            <?php endif; ?>
                            
                            <li class="user-menu">
                                <span>Xin chào, <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
                                <a href="index.php?controller=auth&action=logout" class="btn-logout">Đăng xuất</a>
                            </li>
                        <?php else: ?>
                            <li><a href="index.php?controller=auth&action=login">Đăng nhập</a></li>
                            <li><a href="index.php?controller=auth&action=register">Đăng ký</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <main class="main-content">
        <?php if (isset($_SESSION['thành_công'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo htmlspecialchars($_SESSION['thành_công']); 
                    unset($_SESSION['thành_công']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['lỗi'])): ?>
            <div class="alert alert-error">
                <?php 
                    echo htmlspecialchars($_SESSION['lỗi']); 
                    unset($_SESSION['lỗi']);
                ?>
            </div>
        <?php endif; ?>
