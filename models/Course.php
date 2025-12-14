<?php
class Course {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    private function sanitizeText($text)
    {
        return trim($text);
    }

    /**
     * Lấy tất cả khóa học (kèm tên giảng viên + tên danh mục)
     */
    public function lấyTấtCả()
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                COALESCE(u.fullname, u.username, '') AS `tên_giảng_viên`,
                COALESCE(cat.name, '') AS `tên_danh_mục`
            FROM courses c
            LEFT JOIN users u ON c.instructor_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            ORDER BY c.created_at DESC, c.id DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách khóa học theo danh mục
     */
    public function lấyTheoDanhMục($categoryId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                COALESCE(u.fullname, u.username, '') AS `tên_giảng_viên`,
                COALESCE(cat.name, '') AS `tên_danh_mục`
            FROM courses c
            LEFT JOIN users u ON c.instructor_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.category_id = ?
            ORDER BY c.created_at DESC, c.id DESC
        ");

        $stmt->execute([(int)$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết khóa học theo ID (kèm tên giảng viên + tên danh mục)
     */
    public function lấyTheoId($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                COALESCE(u.fullname, u.username, '') AS `tên_giảng_viên`,
                COALESCE(cat.name, '') AS `tên_danh_mục`
            FROM courses c
            LEFT JOIN users u ON c.instructor_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.id = ?
            LIMIT 1
        ");

        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm kiếm khóa học theo từ khóa
     */
    public function tìmKiếm($từ_khóa)
    {
        $kw = '%' . $this->sanitizeText($từ_khóa) . '%';

        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                COALESCE(u.fullname, u.username, '') AS `tên_giảng_viên`,
                COALESCE(cat.name, '') AS `tên_danh_mục`
            FROM courses c
            LEFT JOIN users u ON c.instructor_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.title LIKE ? OR c.description LIKE ?
            ORDER BY c.created_at DESC, c.id DESC
        ");

        $stmt->execute([$kw, $kw]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả khóa học do một giảng viên tạo (theo instructor_id)
    public function getAllCoursesByInstructor($instructorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE instructor_id = ?");
        $stmt->execute([(int)$instructorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách khóa học theo giảng viên (kèm tên danh mục)
     */
    public function lấyTheoGiảngViên($instructorId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.*,
                COALESCE(cat.name, '') AS `tên_danh_mục`
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.instructor_id = ?
            ORDER BY c.created_at DESC, c.id DESC
        ");
        $stmt->execute([(int)$instructorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm số lượng khóa học theo giảng viên
     */
    public function đếmKhóaHọcTheoGiảngViên($instructorId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM courses WHERE instructor_id = ?");
        $stmt->execute([(int)$instructorId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Xóa tất cả khóa học theo giảng viên
     */
    public function xóaTheoGiảngViên($instructorId)
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE instructor_id = ?");
        return $stmt->execute([(int)$instructorId]);
    }

    /**
     * Đếm tất cả khóa học
     */
    public function đếmTấtCả()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM courses");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Đếm số học viên đăng ký một khóa học
     */
    public function đếmHọcViên($courseId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
        $stmt->execute([(int)$courseId]);
        return (int)$stmt->fetchColumn();
    }

    // Lấy thông tin chi tiết một khóa học theo id
    public function getCourse($courseId)
    {
        return $this->lấyTheoId($courseId);
    }

    // Tạo mới một khóa học
    public function createCourse($data)
    {
        $title       = $this->sanitizeText($data['title']);
        $description = $this->sanitizeText($data['description']);

        $stmt = $this->db->prepare("
            INSERT INTO courses 
            (title, description, instructor_id, category_id, price, duration_weeks, level, image, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        return $stmt->execute([
            $title,
            $description,
            (int)$data['instructor_id'],   
            (int)$data['category_id'],     
            (float)$data['price'],         
            (int)$data['duration_weeks'],  
            $this->sanitizeText($data['level']), 
            $this->sanitizeText($data['image'])  
        ]);
    }

    // Cập nhật thông tin một khóa học
    public function updateCourse($courseId, $data)
    {
        $title       = $this->sanitizeText($data['title']);
        $description = $this->sanitizeText($data['description']);

        $stmt = $this->db->prepare("
            UPDATE courses 
            SET title = ?, 
                description = ?, 
                category_id = ?, 
                price = ?, 
                duration_weeks = ?, 
                level = ?, 
                image = ?, 
                updated_at = NOW() 
            WHERE id = ? AND instructor_id = ?
        ");

        return $stmt->execute([
            $title,
            $description,
            (int)$data['category_id'],
            (float)$data['price'],
            (int)$data['duration_weeks'],
            $this->sanitizeText($data['level']),
            $this->sanitizeText($data['image']),
            (int)$courseId,
            (int)$data['instructor_id']
        ]);
    }

    // Xóa một khóa học 
    public function deleteCourse($courseId, $instructorId)
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ? AND instructor_id = ?");
        return $stmt->execute([(int)$courseId, (int)$instructorId]);
    }

    /**
     * Xóa một khóa học (phiên bản admin)
     */
    public function xóa($courseId)
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([(int)$courseId]);
    }
}
