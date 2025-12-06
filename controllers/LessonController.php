<?php
class LessonController {
    private $lessonModel;
    private $courseModel;
    public function __construct($db)
    {
        session_start();
        $this->ensureInstructor();
        $this->lessonModel = new Lesson($db);
        $this->courseModel = new Course($db);
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
}
