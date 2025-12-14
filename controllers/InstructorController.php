<?php
class InstructorController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        
        // Kiểm tra session đã được khởi động chưa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->ensureInstructor();
    }

    // Kiểm tra người dùng hiện tại có phải giảng viên không (role = 1)
    private function ensureInstructor()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    // Action hiển thị trang dashboard giảng viên
    public function dashboard()
    {
        require_once 'views/instructor/dashboard.php';
    }
    
    public function myCourses()
    {
        header('Location: index.php?controller=course&action=list');
        exit;
    }
}
