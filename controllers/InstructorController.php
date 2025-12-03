<?php
/**
 * Controller Instructor - Xử lý các chức năng giảng viên
 */
class InstructorController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
        
        // Kiểm tra quyền giảng viên
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 1) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập với vai trò giảng viên!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
    
    /**
     * Dashboard giảng viên
     */
    public function dashboard()
    {
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $danh_sách_khóa_học = $courseModel->lấyTheoGiảngViên($_SESSION['user_id']);
        
        require_once 'views/instructor/dashboard.php';
    }
    
    /**
     * Khóa học của tôi
     */
    public function my_courses()
    {
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $danh_sách_khóa_học = $courseModel->lấyTheoGiảngViên($_SESSION['user_id']);
        
        require_once 'views/instructor/my_courses.php';
    }
    
    /**
     * Tạo khóa học
     */
    public function create_course()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Course.php';
            
            $courseModel = new Course($this->db);
            $courseModel->title = $_POST['title'] ?? '';
            $courseModel->description = $_POST['description'] ?? '';
            $courseModel->instructor_id = $_SESSION['user_id'];
            $courseModel->category_id = $_POST['category_id'] ?? 1;
            $courseModel->price = $_POST['price'] ?? 0;
            $courseModel->duration_weeks = $_POST['duration_weeks'] ?? 0;
            $courseModel->level = $_POST['level'] ?? 'Beginner';
            $courseModel->image = $_POST['image'] ?? '';
            
            if ($courseModel->tạo()) {
                $_SESSION['thành_công'] = 'Tạo khóa học thành công!';
                header('Location: index.php?controller=instructor&action=my_courses');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo khóa học thất bại!';
            }
        }
        
        require_once 'models/Category.php';
        $categoryModel = new Category($this->db);
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/instructor/course/create.php';
    }
    
    /**
     * Chỉnh sửa khóa học
     */
    public function edit_course()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $khóa_học = $courseModel->lấyTheoId($id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền chỉnh sửa khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseModel->id = $id;
            $courseModel->title = $_POST['title'] ?? '';
            $courseModel->description = $_POST['description'] ?? '';
            $courseModel->category_id = $_POST['category_id'] ?? 1;
            $courseModel->price = $_POST['price'] ?? 0;
            $courseModel->duration_weeks = $_POST['duration_weeks'] ?? 0;
            $courseModel->level = $_POST['level'] ?? 'Beginner';
            $courseModel->image = $_POST['image'] ?? '';
            
            if ($courseModel->cậpNhật()) {
                $_SESSION['thành_công'] = 'Cập nhật khóa học thành công!';
                header('Location: index.php?controller=instructor&action=my_courses');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Cập nhật khóa học thất bại!';
            }
        }
        
        require_once 'models/Category.php';
        $categoryModel = new Category($this->db);
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/instructor/course/edit.php';
    }
    
    /**
     * Quản lý khóa học
     */
    public function manage_course()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        
        $courseModel = new Course($this->db);
        $lessonModel = new Lesson($this->db);
        
        $khóa_học = $courseModel->lấyTheoId($id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền quản lý khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        $danh_sách_bài_học = $lessonModel->lấyTheoKhóaHọc($id);
        
        require_once 'views/instructor/course/manage.php';
    }
    
    /**
     * Xóa khóa học
     */
    public function delete_course()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/Course.php';
            
            $courseModel = new Course($this->db);
            $khóa_học = $courseModel->lấyTheoId($id);
            
            // Kiểm tra quyền sở hữu
            if ($khóa_học['instructor_id'] == $_SESSION['user_id']) {
                if ($courseModel->xóa($id)) {
                    $_SESSION['thành_công'] = 'Xóa khóa học thành công!';
                } else {
                    $_SESSION['lỗi'] = 'Xóa khóa học thất bại!';
                }
            } else {
                $_SESSION['lỗi'] = 'Bạn không có quyền xóa khóa học này!';
            }
        }
        
        header('Location: index.php?controller=instructor&action=my_courses');
        exit();
    }
    
    /**
     * Tạo bài học
     */
    public function create_lesson()
    {
        $course_id = $_GET['course_id'] ?? null;
        
        if (!$course_id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $khóa_học = $courseModel->lấyTheoId($course_id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền tạo bài học cho khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Lesson.php';
            
            $lessonModel = new Lesson($this->db);
            $lessonModel->course_id = $course_id;
            $lessonModel->title = $_POST['title'] ?? '';
            $lessonModel->content = $_POST['content'] ?? '';
            $lessonModel->video_url = $_POST['video_url'] ?? '';
            $lessonModel->order = $_POST['order'] ?? 0;
            
            if ($lessonModel->tạo()) {
                $_SESSION['thành_công'] = 'Tạo bài học thành công!';
                header('Location: index.php?controller=instructor&action=manage_course&id=' . $course_id);
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo bài học thất bại!';
            }
        }
        
        require_once 'views/instructor/lessons/create.php';
    }
    
    /**
     * Chỉnh sửa bài học
     */
    public function edit_lesson()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Lesson.php';
        require_once 'models/Course.php';
        
        $lessonModel = new Lesson($this->db);
        $courseModel = new Course($this->db);
        
        $bài_học = $lessonModel->lấyTheoId($id);
        $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền chỉnh sửa bài học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lessonModel->id = $id;
            $lessonModel->title = $_POST['title'] ?? '';
            $lessonModel->content = $_POST['content'] ?? '';
            $lessonModel->video_url = $_POST['video_url'] ?? '';
            $lessonModel->order = $_POST['order'] ?? 0;
            
            if ($lessonModel->cậpNhật()) {
                $_SESSION['thành_công'] = 'Cập nhật bài học thành công!';
                header('Location: index.php?controller=instructor&action=manage_course&id=' . $bài_học['course_id']);
                exit();
            } else {
                $_SESSION['lỗi'] = 'Cập nhật bài học thất bại!';
            }
        }
        
        require_once 'views/instructor/lessons/edit.php';
    }
    
    /**
     * Xóa bài học
     */
    public function delete_lesson()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/Lesson.php';
            require_once 'models/Course.php';
            
            $lessonModel = new Lesson($this->db);
            $courseModel = new Course($this->db);
            
            $bài_học = $lessonModel->lấyTheoId($id);
            $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
            
            // Kiểm tra quyền sở hữu
            if ($khóa_học['instructor_id'] == $_SESSION['user_id']) {
                if ($lessonModel->xóa($id)) {
                    $_SESSION['thành_công'] = 'Xóa bài học thành công!';
                    header('Location: index.php?controller=instructor&action=manage_course&id=' . $bài_học['course_id']);
                    exit();
                } else {
                    $_SESSION['lỗi'] = 'Xóa bài học thất bại!';
                }
            } else {
                $_SESSION['lỗi'] = 'Bạn không có quyền xóa bài học này!';
            }
        }
        
        header('Location: index.php?controller=instructor&action=my_courses');
        exit();
    }
    
    /**
     * Tải lên tài liệu
     */
    public function upload_material()
    {
        $lesson_id = $_GET['lesson_id'] ?? null;
        
        if (!$lesson_id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Lesson.php';
        require_once 'models/Course.php';
        
        $lessonModel = new Lesson($this->db);
        $courseModel = new Course($this->db);
        
        $bài_học = $lessonModel->lấyTheoId($lesson_id);
        $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền tải lên tài liệu cho bài học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Material.php';
            
            $materialModel = new Material($this->db);
            $materialModel->lesson_id = $lesson_id;
            $materialModel->filename = $_POST['filename'] ?? '';
            $materialModel->file_path = $_POST['file_path'] ?? '';
            $materialModel->file_type = $_POST['file_type'] ?? '';
            
            if ($materialModel->tảiLên()) {
                $_SESSION['thành_công'] = 'Tải lên tài liệu thành công!';
                header('Location: index.php?controller=instructor&action=manage_course&id=' . $bài_học['course_id']);
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tải lên tài liệu thất bại!';
            }
        }
        
        require_once 'views/instructor/materials/upload.php';
    }
    
    /**
     * Danh sách học viên
     */
    public function list_students()
    {
        $course_id = $_GET['course_id'] ?? null;
        
        if (!$course_id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        require_once 'models/Enrollment.php';
        
        $courseModel = new Course($this->db);
        $enrollmentModel = new Enrollment($this->db);
        
        $khóa_học = $courseModel->lấyTheoId($course_id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền xem danh sách học viên của khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        $danh_sách_học_viên = $enrollmentModel->lấyHọcViênCủaKhóaHọc($course_id);
        
        require_once 'views/instructor/students/list.php';
    }
}
