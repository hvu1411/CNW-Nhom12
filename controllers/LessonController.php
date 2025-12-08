<?php
class LessonController {
    private $lessonModel;
    private $courseModel;
    private $materialModel;
    public function __construct($db)
    {
        session_start();
        $this->ensureInstructor();
        $this->lessonModel = new Lesson($db);
        $this->courseModel = new Course($db);
        $this->materialModel = new Material($db);
    }

    // Kiểm tra người dùng hiện tại có phải giảng viên không (role = 1)
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
    // Nếu không phải, chuyển về danh sách khóa học
    private function ensureCourseOwner($courseId)
    {
        $course = $this->courseModel->getCourse($courseId);
        if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
            header('Location: index.php?controller=course&action=list');
            exit;
        }
        return $course;
    }

    // Hiển thị danh sách bài học của một khóa học
    public function manage()
    {
        $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        $course = $this->ensureCourseOwner($courseId);
        $lessons = $this->lessonModel->getLessonsByCourse($courseId);
        include 'views/lessons/manage.php';
    }

    public function create()
    {
        $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        $course = $this->ensureCourseOwner($courseId);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $this->sanitize($_POST);
            if (empty($post['title'])) {
                $errors[] = 'Tiêu đề không được để trống';
            }

            if (empty($errors)) {
                $data = [
                    'course_id' => $courseId,
                    'title'     => $post['title'],
                    'content'   => $post['content'] ?? '',
                    'video_url' => $post['video_url'] ?? '',
                    'order'     => $post['order'] ?? 1
                ];
                $this->lessonModel->createLesson($data);
                header('Location: index.php?controller=lesson&action=manage&course_id=' . $courseId);
                exit;
            }
        }

        include 'views/lessons/create.php';
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $lesson = $this->lessonModel->getLesson($id);
        if (!$lesson) {
            header('Location: index.php?controller=course&action=list');
            exit;
        }

        $course   = $this->ensureCourseOwner($lesson['course_id']);
        $courseId = $course['id'];

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $this->sanitize($_POST);
            if (empty($post['title'])) {
                $errors[] = 'Tiêu đề không được để trống';
            }

            if (empty($errors)) {
                $data = [
                    'course_id' => $courseId,
                    'title'     => $post['title'],
                    'content'   => $post['content'] ?? '',
                    'video_url' => $post['video_url'] ?? '',
                    'order'     => $post['order'] ?? 1
                ];
                $this->lessonModel->updateLesson($id, $data);
                header('Location: index.php?controller=lesson&action=manage&course_id=' . $courseId);
                exit;
            }
        }

        include 'views/lessons/edit.php';
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $lesson = $this->lessonModel->getLesson($id);
        if (!$lesson) {
            header('Location: index.php?controller=course&action=list');
            exit;
        }

        $course = $this->ensureCourseOwner($lesson['course_id']);
        $this->lessonModel->deleteLesson($id, $course['id']);
        header('Location: index.php?controller=lesson&action=manage&course_id=' . $course['id']);
        exit;
    }

    public function uploadMaterial()
    {
        $lessonId = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 0;
        $lesson = $this->lessonModel->getLesson($lessonId);
        if (!$lesson) {
            header('Location: index.php?controller=course&action=list');
            exit;
        }

        $course = $this->ensureCourseOwner($lesson['course_id']);

        $errors  = [];
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
                $errors[] = 'Vui lòng chọn file hợp lệ.';
            } else {
                $allowedTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                ];

                $fileType = mime_content_type($_FILES['file']['tmp_name']);

                if (!in_array($fileType, $allowedTypes)) {
                    $errors[] = 'Định dạng file không được hỗ trợ.';
                } else {
                    // Thư mục lưu file trên server
                    $uploadDir = __DIR__ . '/../uploads/materials/';
                    if (!is_dir($uploadDir)) {
                        // Tạo thư mục nếu chưa tồn tại
                        mkdir($uploadDir, 0777, true);
                    }

                    $safeName  = time() . '_' . basename($_FILES['file']['name']);
                    $safeName  = preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $safeName);
                    $targetPath = $uploadDir . $safeName;

                    // Di chuyển file từ thư mục tạm sang thư mục lưu trữ
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                        $relativePath = 'uploads/materials/' . $safeName;
                        $this->materialModel->createMaterial(
                            $lessonId,
                            $safeName,      
                            $relativePath,  
                            $fileType 
                        );

                        $success = 'Tải lên tài liệu thành công.';
                    } else {
                        $errors[] = 'Không thể lưu file trên server.';
                    }
                }
            }
        }

        $materials = $this->materialModel->getMaterialsByLesson($lessonId);
        include 'views/materials/upload.php';
    }
}
