<?php
class EnrollmentController {
    private $enrollmentModel;
    private $courseModel;

    public function __construct($db)
    {
        session_start();
        $this->ensureInstructor();

        $this->enrollmentModel = new Enrollment($db);
        $this->courseModel     = new Course($db);
    }

    // Chỉ cho phép giảng viên (role = 1)
    private function ensureInstructor()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    private function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    // Đảm bảo khóa học thuộc về giảng viên hiện tại
    private function ensureCourseOwner($courseId)
    {
        $course = $this->courseModel->getCourse($courseId);
        if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
            header('Location: index.php?controller=course&action=list');
            exit;
        }
        return $course;
    }

    // Xem danh sách học viên của 1 khóa học
    public function students()
    {
        $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        $course = $this->ensureCourseOwner($courseId);
        $students = $this->enrollmentModel->getStudentsByCourse($courseId);
        include 'views/students/list.php';
    }

    // Cập nhật trạng thái,tiến độ cho 1 học viên trong khóa
    public function updateProgress()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=course&action=list');
            exit;
        }

        $post     = $this->sanitize($_POST);
        $courseId = (int)$post['course_id'];

        $course   = $this->ensureCourseOwner($courseId);
        $enrollmentId = (int)$post['enrollment_id'];
        $status       = $post['status'] ?? 'active';
        $progress     = (int)($post['progress'] ?? 0);
        if ($progress < 0)   $progress = 0;
        if ($progress > 100) $progress = 100;
        $this->enrollmentModel->updateProgress($enrollmentId, $status, $progress);
        header('Location: index.php?controller=enrollment&action=students&course_id=' . $courseId);
        exit;
    }
}
