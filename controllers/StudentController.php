<?php
/**
 * Controller Student - Xử lý các chức năng học viên
 */
class StudentController
{
    private $db;
    private $enrollmentModel;
    private $courseModel;
    private $lessonModel;

    public function __construct($db)
    {
        $this->db = $db;
        
        // Kiểm tra quyền học viên
        $this->checkStudentRole();
        
        // Tải các model
        $this->loadModels();
    }

    /**
     * Kiểm tra quyền học viên
     */
    private function checkStudentRole()
    {
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 0) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập với vai trò học viên!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }

    /**
     * Tải các model
     */
    private function loadModels()
    {
        require_once 'models/Enrollment.php';
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        
        $this->enrollmentModel = new Enrollment($this->db);
        $this->courseModel = new Course($this->db);
        $this->lessonModel = new Lesson($this->db);
    }

    /**
     * Dashboard học viên
     */
    public function dashboard()
    {
        $danh_sách_khóa_học = $this->enrollmentModel->lấyKhóaHọcCủaHọcViên($_SESSION['user_id']);
        
        require_once 'views/student/dashboard.php';
    }

    /**
     * Khóa học của tôi
     */
    public function my_courses()
    {
        $danh_sách_khóa_học = $this->enrollmentModel->lấyKhóaHọcCủaHọcViên($_SESSION['user_id']);
        
        require_once 'views/student/my_courses.php';
    }

    /**
     * Tiến độ khóa học
     */
    public function course_progress()
    {
        $course_id = $_GET['course_id'] ?? null;
        
        if (!$course_id || !is_numeric($course_id)) {
            header('Location: index.php?controller=student&action=my_courses');
            exit();
        }
        
        // Kiểm tra đã đăng ký chưa
        if (!$this->enrollmentModel->kiểmTraĐãĐăngKý($course_id, $_SESSION['user_id'])) {
            $_SESSION['lỗi'] = 'Bạn chưa đăng ký khóa học này!';
            header('Location: index.php?controller=course&action=detail&id=' . $course_id);
            exit();
        }
        
        // Lấy thông tin khóa học và danh sách bài học
        $khóa_học = $this->courseModel->lấyTheoId($course_id);
        $danh_sách_bài_học = $this->lessonModel->lấyTheoKhóaHọc($course_id);
        
        require_once 'views/student/course_progress.php';
    }
}
