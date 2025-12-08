<?php
/**
 * Controller Enrollment - Xử lý đăng ký khóa học
 */
class EnrollmentController
{
    private $db;
    private $enrollmentModel;

    public function __construct($db)
    {
        $this->db = $db;
        require_once 'models/Enrollment.php';
        $this->enrollmentModel = new Enrollment($this->db);
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
            exit();
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
        } else {
            $_SESSION['lỗi'] = 'Đăng ký khóa học thất bại!';
            header('Location: index.php?controller=course&action=detail&id=' . $this->enrollmentModel->course_id);
        }
        exit();
    }
}
