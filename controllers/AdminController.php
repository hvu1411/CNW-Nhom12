<?php
/**
 * Controller Admin - Xử lý các chức năng quản trị
 */
class AdminController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
        
        // Kiểm tra quyền admin
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 2) {
            $_SESSION['lỗi'] = 'Bạn không có quyền truy cập trang này!';
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        require_once 'models/User.php';
        require_once 'models/Course.php';
        require_once 'models/Enrollment.php';
        
        $userModel = new User($this->db);
        $courseModel = new Course($this->db);
        
        // Thống kê
        $tổng_người_dùng = count($userModel->lấyTấtCả());
        $tổng_giảng_viên = count($userModel->lấyTheoVaiTrò(1));
        $tổng_học_viên = count($userModel->lấyTheoVaiTrò(0));
        $tổng_khóa_học = count($courseModel->lấyTấtCả());
        
        require_once 'views/admin/dashboard.php';
    }
    
    /**
     * Quản lý người dùng
     */
    public function manage_users()
    {
        require_once 'models/User.php';
        
        $userModel = new User($this->db);
        $danh_sách_người_dùng = $userModel->lấyTấtCả();
        
        require_once 'views/admin/users/manage.php';
    }
    
    /**
     * Xóa người dùng
     */
    public function delete_user()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id && $id != $_SESSION['user_id']) {
            require_once 'models/User.php';
            
            $userModel = new User($this->db);
            if ($userModel->xóa($id)) {
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
        require_once 'models/Category.php';
        
        $categoryModel = new Category($this->db);
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/admin/categories/list.php';
    }
    
    /**
     * Tạo danh mục
     */
    public function create_category()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Category.php';
            
            $categoryModel = new Category($this->db);
            $categoryModel->name = $_POST['name'] ?? '';
            $categoryModel->description = $_POST['description'] ?? '';
            
            if ($categoryModel->tạo()) {
                $_SESSION['thành_công'] = 'Tạo danh mục thành công!';
                header('Location: index.php?controller=admin&action=list_categories');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo danh mục thất bại!';
            }
        }
        
        require_once 'views/admin/categories/create.php';
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
        
        require_once 'models/Category.php';
        
        $categoryModel = new Category($this->db);
        
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
        require_once 'views/admin/categories/edit.php';
    }
    
    /**
     * Xóa danh mục
     */
    public function delete_category()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/Category.php';
            
            $categoryModel = new Category($this->db);
            if ($categoryModel->xóa($id)) {
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
        require_once 'models/User.php';
        require_once 'models/Course.php';
        require_once 'models/Category.php';
        
        $userModel = new User($this->db);
        $courseModel = new Course($this->db);
        $categoryModel = new Category($this->db);
        
        $danh_sách_người_dùng = $userModel->lấyTấtCả();
        $danh_sách_khóa_học = $courseModel->lấyTấtCả();
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/admin/reports/statistics.php';
    }
    
    // ========== QUẢN LÝ GIẢNG VIÊN ==========
    
    /**
     * Danh sách giảng viên
     */
    public function list_instructors()
    {
        require_once 'models/User.php';
        require_once 'models/Course.php';
        
        $userModel = new User($this->db);
        $courseModel = new Course($this->db);
        
        $danh_sách_giảng_viên = $userModel->lấyTheoVaiTrò(1);
        
        // Đếm số khóa học của mỗi giảng viên
        foreach ($danh_sách_giảng_viên as &$gv) {
            $gv['số_khóa_học'] = $courseModel->đếmKhóaHọcTheoGiảngViên($gv['id']);
        }
        
        require_once 'views/admin/instructors/list.php';
    }
    
    /**
     * Thêm giảng viên mới
     */
    public function create_instructor()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/User.php';
            
            $userModel = new User($this->db);
            
            // Kiểm tra mật khẩu khớp
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['lỗi'] = 'Mật khẩu xác nhận không khớp!';
                require_once 'views/admin/instructors/create.php';
                return;
            }
            
            // Kiểm tra username đã tồn tại
            if ($userModel->kiểmTraUsernameTồnTại($_POST['username'])) {
                $_SESSION['lỗi'] = 'Tên đăng nhập đã tồn tại!';
                require_once 'views/admin/instructors/create.php';
                return;
            }
            
            // Kiểm tra email đã tồn tại
            if ($userModel->kiểmTraEmailTồnTại($_POST['email'])) {
                $_SESSION['lỗi'] = 'Email đã được sử dụng!';
                require_once 'views/admin/instructors/create.php';
                return;
            }
            
            $userModel->username = $_POST['username'];
            $userModel->email = $_POST['email'];
            $userModel->fullname = $_POST['fullname'];
            $userModel->password = $_POST['password'];
            $userModel->role = 1; // Giảng viên
            
            if ($userModel->đăngKý()) {
                // Cập nhật số điện thoại nếu có
                if (!empty($_POST['phone'])) {
                    $lastId = $this->db->lastInsertId();
                    $userModel->cậpNhậtProfile($lastId, $_POST['fullname'], $_POST['email'], $_POST['phone']);
                }
                
                $_SESSION['thành_công'] = 'Tạo giảng viên thành công!';
                header('Location: index.php?controller=admin&action=list_instructors');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo giảng viên thất bại!';
            }
        }
        
        require_once 'views/admin/instructors/create.php';
    }
    
    /**
     * Sửa giảng viên
     */
    public function edit_instructor()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=admin&action=list_instructors');
            exit();
        }
        
        require_once 'models/User.php';
        $userModel = new User($this->db);
        $giảng_viên = $userModel->lấyTheoId($id);
        
        // Kiểm tra có phải giảng viên không
        if (!$giảng_viên || $giảng_viên['role'] != 1) {
            $_SESSION['lỗi'] = 'Không tìm thấy giảng viên!';
            header('Location: index.php?controller=admin&action=list_instructors');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra mật khẩu mới nếu có
            if (!empty($_POST['new_password'])) {
                if ($_POST['new_password'] !== $_POST['confirm_password']) {
                    $_SESSION['lỗi'] = 'Mật khẩu xác nhận không khớp!';
                    require_once 'views/admin/instructors/edit.php';
                    return;
                }
                if (strlen($_POST['new_password']) < 6) {
                    $_SESSION['lỗi'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
                    require_once 'views/admin/instructors/edit.php';
                    return;
                }
                $userModel->đổiMậtKhẩu($id, $_POST['new_password']);
            }
            
            // Cập nhật thông tin
            $userModel->cậpNhậtProfile($id, $_POST['fullname'], $_POST['email'], $_POST['phone'] ?? '');
            
            $_SESSION['thành_công'] = 'Cập nhật giảng viên thành công!';
            header('Location: index.php?controller=admin&action=list_instructors');
            exit();
        }
        
        require_once 'views/admin/instructors/edit.php';
    }
    
    /**
     * Xóa giảng viên
     */
    public function delete_instructor()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/User.php';
            require_once 'models/Course.php';
            
            $userModel = new User($this->db);
            $giảng_viên = $userModel->lấyTheoId($id);
            
            // Kiểm tra có phải giảng viên không
            if ($giảng_viên && $giảng_viên['role'] == 1) {
                // Xóa tất cả khóa học của giảng viên
                $courseModel = new Course($this->db);
                $courseModel->xóaTheoGiảngViên($id);
                
                // Xóa giảng viên
                if ($userModel->xóa($id)) {
                    $_SESSION['thành_công'] = 'Xóa giảng viên thành công!';
                } else {
                    $_SESSION['lỗi'] = 'Xóa giảng viên thất bại!';
                }
            } else {
                $_SESSION['lỗi'] = 'Không tìm thấy giảng viên!';
            }
        }
        
        header('Location: index.php?controller=admin&action=list_instructors');
        exit();
    }
    
    /**
     * Xem khóa học của giảng viên
     */
    public function view_instructor_courses()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=admin&action=list_instructors');
            exit();
        }
        
        require_once 'models/User.php';
        require_once 'models/Course.php';
        require_once 'models/Enrollment.php';
        
        $userModel = new User($this->db);
        $courseModel = new Course($this->db);
        
        $giảng_viên = $userModel->lấyTheoId($id);
        
        if (!$giảng_viên || $giảng_viên['role'] != 1) {
            $_SESSION['lỗi'] = 'Không tìm thấy giảng viên!';
            header('Location: index.php?controller=admin&action=list_instructors');
            exit();
        }
        
        $danh_sách_khóa_học = $courseModel->lấyTheoGiảngViên($id);
        
        // Đếm học viên cho mỗi khóa học
        $tổng_học_viên = 0;
        foreach ($danh_sách_khóa_học as &$kh) {
            $kh['số_học_viên'] = $courseModel->đếmHọcViên($kh['id']);
            $tổng_học_viên += $kh['số_học_viên'];
        }
        
        require_once 'views/admin/instructors/courses.php';
    }
    
    /**
     * Xóa khóa học (từ trang quản lý giảng viên)
     */
    public function delete_course()
    {
        $id = $_GET['id'] ?? null;
        $instructor_id = $_GET['instructor_id'] ?? null;
        
        if ($id) {
            require_once 'models/Course.php';
            
            $courseModel = new Course($this->db);
            if ($courseModel->xóa($id)) {
                $_SESSION['thành_công'] = 'Xóa khóa học thành công!';
            } else {
                $_SESSION['lỗi'] = 'Xóa khóa học thất bại!';
            }
        }
        
        if ($instructor_id) {
            header('Location: index.php?controller=admin&action=view_instructor_courses&id=' . $instructor_id);
        } else {
            header('Location: index.php?controller=admin&action=list_instructors');
        }
        exit();
    }
}
