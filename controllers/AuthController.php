<?php
/**
 * Controller Auth - Xử lý đăng nhập và đăng ký
 */
class AuthController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
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
        require_once 'models/User.php';
        
        $userModel = new User($this->db);
        $userModel->username = $_POST['username'] ?? '';
        $userModel->password = $_POST['password'] ?? '';
        
        if ($userModel->đăngNhập()) {
            // Lưu thông tin vào session
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
        require_once 'models/User.php';
        
        $userModel = new User($this->db);
        $userModel->username = $_POST['username'] ?? '';
        $userModel->email = $_POST['email'] ?? '';
        $userModel->password = $_POST['password'] ?? '';
        $userModel->fullname = $_POST['fullname'] ?? '';
        $userModel->role = 0; // Mặc định là học viên
        
        // Validate
        $xác_nhận_mật_khẩu = $_POST['confirm_password'] ?? '';
        if ($userModel->password !== $xác_nhận_mật_khẩu) {
            $_SESSION['lỗi'] = 'Mật khẩu xác nhận không khớp!';
            header('Location: index.php?controller=auth&action=register');
            exit();
        }
        
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
