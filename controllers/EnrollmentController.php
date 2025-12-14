<?php
/**
 * Controller Enrollment - Xử lý đăng ký khóa học
 */
class EnrollmentController
{
    private $db;
    private $enrollmentModel;
    private $courseModel;

    public function __construct($db)
    {
        $this->db = $db;
        
        // Start session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Load models với đường dẫn chính xác
        require_once __DIR__ . '/../models/Enrollment.php';
        require_once __DIR__ . '/../models/Course.php';
        
        $this->enrollmentModel = new Enrollment($this->db);
        $this->courseModel = new Course($this->db);
    }

    private function sanitize($data)
    {
        $clean = [];
        foreach ((array)$data as $k => $v) {
            $clean[$k] = is_string($v) ? trim($v) : $v;
        }
        return $clean;
    }

    private function ensureInstructor()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || (int)$_SESSION['role'] !== 1) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    private function ensureCourseOwner($courseId)
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = ? AND instructor_id = ? LIMIT 1');
        $stmt->execute([(int)$courseId, (int)$_SESSION['user_id']]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$course) {
            header('Location: index.php?controller=instructor&action=dashboard');
            exit;
        }
        return $course;
    }

    /**
     * Danh sách học viên của khóa học (giảng viên)
     */
    public function students()
    {
        $this->ensureInstructor();

        $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        if ($courseId <= 0) {
            header('Location: index.php?controller=instructor&action=dashboard');
            exit;
        }

        $course = $this->ensureCourseOwner($courseId);
        $students = $this->enrollmentModel->getStudentsByCourse($courseId);

        include __DIR__ . '/../views/instructor/students/list.php';
    }

    /**
     * Cập nhật tiến độ/trạng thái học viên (giảng viên)
     */
    public function updateProgress()
    {
        $this->ensureInstructor();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=instructor&action=dashboard');
            exit;
        }

        $post = $this->sanitize($_POST);
        $courseId = (int)($post['course_id'] ?? 0);
        $enrollmentId = (int)($post['enrollment_id'] ?? 0);
        $status = $post['status'] ?? 'active';
        $progress = (int)($post['progress'] ?? 0);

        if ($progress < 0) $progress = 0;
        if ($progress > 100) $progress = 100;

        $this->ensureCourseOwner($courseId);
        if ($enrollmentId > 0) {
            $this->enrollmentModel->updateProgress($enrollmentId, $status, $progress);
        }

        header('Location: index.php?controller=enrollment&action=students&course_id=' . $courseId);
        exit;
    }

    /**
     * Đăng ký khóa học
     */
    public function enroll()
    {
        // Kiểm tra đăng nhập
        if (!$this->checkStudentLogin()) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập với vai trò học viên để đăng ký khóa học!';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $course_id = $_POST['course_id'] ?? null;
        
        if (!$course_id || !is_numeric($course_id)) {
            header('Location: index.php');
            exit();
        }
        
        // Kiểm tra đã đăng ký chưa
        if ($this->enrollmentModel->kiểmTraĐãĐăngKý($course_id, $_SESSION['user_id'])) {
            $_SESSION['lỗi'] = 'Bạn đã đăng ký khóa học này rồi!';
            header('Location: index.php?controller=course&action=detail&id=' . $course_id);
            exit();
        }
        
        // Đăng ký khóa học
        $this->performEnrollment($course_id);
    }

    /**
     * Kiểm tra đăng nhập học viên
     */
    private function checkStudentLogin()
    {
        return isset($_SESSION['đã_đăng_nhập']) && $_SESSION['role'] == 0;
    }

    /**
     * Thực hiện đăng ký khóa học
     */
    private function performEnrollment($course_id)
    {
        $this->enrollmentModel->course_id = $course_id;
        $this->enrollmentModel->student_id = $_SESSION['user_id'];
        $this->enrollmentModel->status = 'active';
        $this->enrollmentModel->progress = 0;
        
        if ($this->enrollmentModel->đăngKý()) {
            $_SESSION['thành_công'] = 'Đăng ký khóa học thành công!';
            header('Location: index.php?controller=student&action=my_courses');
            exit;
        } else {
            $_SESSION['lỗi'] = 'Đăng ký khóa học thất bại!';
            header('Location: index.php?controller=course&action=detail&id=' . $this->enrollmentModel->course_id);
            exit;
        }
    }
}