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

    // Lấy tất cả khóa học do một giảng viên tạo (theo instructor_id)
    public function getAllCoursesByInstructor($instructorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE instructor_id = ?");
        $stmt->execute([(int)$instructorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin chi tiết một khóa học theo id
    public function getCourse($courseId)
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([(int)$courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
}
