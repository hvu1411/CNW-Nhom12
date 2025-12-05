<?php
class CourseController {
    private $courseModel;
    public function __construct($db)
    {
        session_start();
        $this->ensureInstructor();
        $this->courseModel = new Course($db);
    }
    private function ensureInstructor()
    {
        // Nếu chưa đăng nhập hoặc không có biến role hoặc role khác 1 (giảng viên)
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

    // Action hiển thị danh sách khóa học 
    public function list()
    {
        $instructorId = $_SESSION['user_id'];
        $courses = $this->courseModel->getAllCoursesByInstructor($instructorId);
        include 'views/courses/manage.php';
    }

    // Action tạo mới khóa học
    public function create()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $this->sanitize($_POST);

            if (empty($post['title'])) {
                $errors[] = 'Tiêu đề không được để trống';
            }

            if (empty($errors)) {
                
                $data = [
                    'title'          => $post['title'],
                    'description'    => $post['description'] ?? '',
                    'instructor_id'  => $_SESSION['user_id'],
                    'category_id'    => $post['category_id'] ?? 1,
                    'price'          => $post['price'] ?? 0,
                    'duration_weeks' => $post['duration_weeks'] ?? 0,
                    'level'          => $post['level'] ?? 'Beginner',
                    'image'          => $post['image'] ?? ''
                ];

                $this->courseModel->createCourse($data);
                header('Location: index.php?controller=course&action=list');
                exit;
            }
        }

        // Nếu chưa submit hoặc có lỗi thì hiển thị lại form tạo khóa học
        include 'views/courses/create.php';
    }

    // Action chỉnh sửa khóa học
    public function edit()
    {
        $errors    = [];

        $courseId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $instructorId = $_SESSION['user_id'];
        $course = $this->courseModel->getCourse($courseId);
        // Nếu không tồn tại hoặc khóa học không thuộc giảng viên hiện tại
        if (!$course || $course['instructor_id'] != $instructorId) {
            header('Location: index.php?controller=course&action=list');
            exit;
        }
        // Nếu form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $this->sanitize($_POST);
            if (empty($post['title'])) {
                $errors[] = 'Tiêu đề không được để trống';
            }

            if (empty($errors)) {
                $data = [
                    'title'          => $post['title'],
                    'description'    => $post['description'] ?? '',
                    'instructor_id'  => $instructorId,     
                    'category_id'    => $post['category_id'] ?? 1,
                    'price'          => $post['price'] ?? 0,
                    'duration_weeks' => $post['duration_weeks'] ?? 0,
                    'level'          => $post['level'] ?? 'Beginner',
                    'image'          => $post['image'] ?? ''
                ];

                $this->courseModel->updateCourse($courseId, $data);
                header('Location: index.php?controller=course&action=list');
                exit;
            }
        }

        // Lần đầu vào trang (GET) hoặc có lỗi validate thì hiển thị form sửa
        include 'views/courses/edit.php';
    }

    // Action xóa khóa học
    public function delete()
    {
        
        $courseId     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $instructorId = $_SESSION['user_id'];
        $this->courseModel->deleteCourse($courseId, $instructorId);
        header('Location: index.php?controller=course&action=list');
        exit;
    }
}
