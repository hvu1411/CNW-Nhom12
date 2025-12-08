<?php
/**
 * Controller Auth - Xử lý đăng nhập và đăng ký
 */
class AuthController
{
    private $db;
    private $userModel;
    
    public function __construct($db)
    {
        $this->db = $db;
        // Load model once and reuse to avoid repeated file includes and object creation
        require_once __DIR__ . '/../models/User.php';
        $this->userModel = new User($this->db);
    }
    
    /**
     * Hiển thị form đăng nhập
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->xửLýĐăngNhập();
        } else {
            require_once 'views/auth/login.php';
        }
    }
    
    /**
     * Xử lý đăng nhập
     */
    private function xửLýĐăngNhập()
    {
        // Read and trim inputs once (faster than repeated direct array access)
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = $this->userModel;
        $userModel->username = $username;
        $userModel->password = $password;

        if ($userModel->đăngNhập()) {
            // Lưu thông tin vào session
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userModel->id;
            $_SESSION['username'] = $userModel->username;
            $_SESSION['fullname'] = $userModel->fullname;
            $_SESSION['role'] = $userModel->role;
            $_SESSION['đã_đăng_nhập'] = true;
            
            // Chuyển hướng theo vai trò
            switch ($userModel->role) {
                case 2: // Admin
                    header('Location: index.php?controller=admin&action=dashboard');
                    break;
                case 1: // Giảng viên
                    header('Location: index.php?controller=instructor&action=dashboard');
                    break;
                case 0: // Học viên
                default:
                    header('Location: index.php?controller=student&action=dashboard');
                    break;
            }
            exit();
        } else {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['lỗi'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
    
    /**
     * Hiển thị form đăng ký
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->xửLýĐăngKý();
        } else {
            require_once 'views/auth/register.php';
        }
    }
    
    /**
     * Xử lý đăng ký
     */
    private function xửLýĐăngKý()
    {
        // Read and trim inputs once
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $fullname = trim($_POST['fullname'] ?? '');
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Basic validation (fast checks before creating model / hashing)
        if ($password === '' || $username === '' || $email === '') {
            $_SESSION['lỗi'] = 'Vui lòng điền đầy đủ thông tin.';
            header('Location: index.php?controller=auth&action=register');
            exit();
        }

        if ($password !== $confirm_password) {
            $_SESSION['lỗi'] = 'Mật khẩu xác nhận không khớp!';
            header('Location: index.php?controller=auth&action=register');
            exit();
        }

        $userModel = $this->userModel;
        $userModel->username = $username;
        $userModel->email = $email;
        $userModel->password = $password;
        $userModel->fullname = $fullname;
        $userModel->role = 0; // Mặc định là học viên

        if ($userModel->đăngKý()) {
            $_SESSION['thành_công'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        } else {
            $_SESSION['lỗi'] = 'Đăng ký thất bại! Tên đăng nhập hoặc email đã tồn tại.';
            header('Location: index.php?controller=auth&action=register');
            exit();
        }
    }
    
    /**
     * Đăng xuất
     */
    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
