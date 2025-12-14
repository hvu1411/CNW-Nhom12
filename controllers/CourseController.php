<?php
/**
 * Controller Course - Xử lý các thao tác liên quan đến khóa học
 */
class CourseController
{
    private $db;
    private $courseModel;
    private $categoryModel;
    private $lessonModel;
    private $enrollmentModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->loadModels();
    }

    /**
     * Tải các model một lần trong constructor
     */
    private function loadModels()
    {
        // QUAN TRỌNG: Sử dụng __DIR__ để đảm bảo đường dẫn chính xác
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Category.php';
        require_once __DIR__ . '/../models/Lesson.php';
        require_once __DIR__ . '/../models/Enrollment.php';
        
        $this->courseModel = new Course($this->db);
        $this->categoryModel = new Category($this->db);
        $this->lessonModel = new Lesson($this->db);
        $this->enrollmentModel = new Enrollment($this->db);
    }

    /**
     * Hiển thị danh sách khóa học
     */
    public function index()
    {
        $category_id = $_GET['category_id'] ?? null;
        
        // Lấy dữ liệu từ cache nếu có (tuỳ chọn)
        $danh_sách_khóa_học = $category_id 
            ? $this->courseModel->lấyTheoDanhMục($category_id)
            : $this->courseModel->lấyTấtCả();
        
        $danh_sách_danh_mục = $this->categoryModel->lấyTấtCả();
        
        require_once __DIR__ . '/../views/courses/index.php';
    }

    /**
     * Hiển thị chi tiết khóa học
     */
    public function detail()
    {
        // Start session nếu chưa có (cần để kiểm tra đăng nhập)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            header('Location: index.php?controller=course&action=index');
            exit();
        }
        
        // Lấy thông tin khóa học
        $khóa_học = $this->courseModel->lấyTheoId($id);
        
        if (!$khóa_học) {
            header('Location: index.php?controller=course&action=index');
            exit();
        }
        
        // Lấy danh sách bài học
        $danh_sách_bài_học = $this->lessonModel->lấyTheoKhóaHọc($id);
        
        // Kiểm tra đã đăng ký chưa (chỉ nếu đã đăng nhập)
        $đã_đăng_ký = isset($_SESSION['user_id']) 
            ? $this->enrollmentModel->kiểmTraĐãĐăngKý($id, $_SESSION['user_id'])
            : false;
        
        require_once __DIR__ . '/../views/courses/detail.php';
    }

    /**
     * Tìm kiếm khóa học
     */
    public function search()
    {
        $từ_khóa = trim($_GET['keyword'] ?? '');
        
        $danh_sách_khóa_học = !empty($từ_khóa) 
            ? $this->courseModel->tìmKiếm($từ_khóa)
            : [];
        
        $danh_sách_danh_mục = $this->categoryModel->lấyTấtCả();
        
        require_once __DIR__ . '/../views/courses/search.php';
    }
}