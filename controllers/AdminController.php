<?php
/**
 * Controller Admin - Xử lý các chức năng quản trị
 */
class AdminController
{
    private $db;
    private $userModel;
    private $courseModel;
    private $categoryModel;
    
    public function __construct($db)
    {
        $this->db = $db;
        // Ensure session started
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Kiểm tra quyền admin
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 2) {
            $_SESSION['lỗi'] = 'Bạn không có quyền truy cập trang này!';
            header('Location: index.php');
            exit();
        }

        // Require and instantiate commonly used models once to reduce include/object creation overhead
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Category.php';

        $this->userModel = new User($this->db);
        $this->courseModel = new Course($this->db);
        $this->categoryModel = new Category($this->db);
    }
    
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        // Use efficient COUNT methods where possible to avoid fetching entire result sets
        $tổng_người_dùng = $this->userModel->đếmTấtCả();
        $tổng_giảng_viên = $this->userModel->đếmTheoVaiTrò(1);
        $tổng_học_viên = $this->userModel->đếmTheoVaiTrò(0);

        // For courses, use a COUNT query directly via the course model if available, otherwise run a lightweight query
        if (method_exists($this->courseModel, 'đếmTấtCả')) {
            $tổng_khóa_học = $this->courseModel->đếmTấtCả();
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM courses");
            $stmt->execute();
            $tổng_khóa_học = (int) $stmt->fetchColumn();
        }

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /**
     * Quản lý người dùng
     */
    public function manage_users()
    {
        $danh_sách_người_dùng = $this->userModel->lấyTấtCả();
        require_once __DIR__ . '/../views/admin/users/manage.php';
    }
    
    /**
     * Xóa người dùng
     */
    public function delete_user()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id && $id != $_SESSION['user_id']) {
            if ($this->userModel->xóa($id)) {
                $_SESSION['thành_công'] = 'Xóa người dùng thành công!';
            } else {
                $_SESSION['lỗi'] = 'Xóa người dùng thất bại!';
            }
        }
        
        header('Location: index.php?controller=admin&action=manage_users');
        exit();
    }
    
    /**
     * Danh sách danh mục
     */
    public function list_categories()
    {
        $danh_sách_danh_mục = $this->categoryModel->lấyTấtCả();
        require_once __DIR__ . '/../views/admin/categories/list.php';
    }
    
    /**
     * Tạo danh mục
     */
    public function create_category()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categoryModel->name = $_POST['name'] ?? '';
            $this->categoryModel->description = $_POST['description'] ?? '';

            if ($this->categoryModel->tạo()) {
                $_SESSION['thành_công'] = 'Tạo danh mục thành công!';
                header('Location: index.php?controller=admin&action=list_categories');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo danh mục thất bại!';
            }
        }
        require_once __DIR__ . '/../views/admin/categories/create.php';
    }
    
    /**
     * Chỉnh sửa danh mục
     */
    public function edit_category()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=admin&action=list_categories');
            exit();
        }
        
        $categoryModel = $this->categoryModel;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel->id = $id;
            $categoryModel->name = $_POST['name'] ?? '';
            $categoryModel->description = $_POST['description'] ?? '';
            
            if ($categoryModel->cậpNhật()) {
                $_SESSION['thành_công'] = 'Cập nhật danh mục thành công!';
                header('Location: index.php?controller=admin&action=list_categories');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Cập nhật danh mục thất bại!';
            }
        }
        
        $danh_mục = $categoryModel->lấyTheoId($id);
        require_once __DIR__ . '/../views/admin/categories/edit.php';
    }
    
    /**
     * Xóa danh mục
     */
    public function delete_category()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            if ($this->categoryModel->xóa($id)) {
                $_SESSION['thành_công'] = 'Xóa danh mục thành công!';
            } else {
                $_SESSION['lỗi'] = 'Xóa danh mục thất bại! Có thể danh mục đang được sử dụng.';
            }
        }
        header('Location: index.php?controller=admin&action=list_categories');
        exit();
    }
    
    /**
     * Thống kê
     */
    public function statistics()
    {
        $danh_sách_người_dùng = $this->userModel->lấyTấtCả();
        $danh_sách_khóa_học = $this->courseModel->lấyTấtCả();
        $danh_sách_danh_mục = $this->categoryModel->lấyTấtCả();

        require_once __DIR__ . '/../views/admin/reports/statistics.php';
    }
}
