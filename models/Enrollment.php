<?php
class Enrollment {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Lấy danh sách học viên đăng ký của 1 khóa học
    public function getStudentsByCourse($courseId)
    {
        // Join enrollments với users để lấy thông tin tài khoản
        $sql = "
            SELECT 
                e.id,
                e.course_id,
                e.student_id,
                e.enrolled_date,
                e.status,
                e.progress,
                u.fullname,
                u.email,
                u.username
            FROM enrollments e
            JOIN users u ON e.student_id = u.id
            WHERE e.course_id = ?
            ORDER BY u.fullname ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int)$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái và tiến độ cho 1 bản ghi enroll
    public function updateProgress($enrollmentId, $status, $progress)
    {
        $stmt = $this->db->prepare("
            UPDATE enrollments
            SET status = ?, progress = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $status,
            (int)$progress,
            (int)$enrollmentId
        ]);
    }
}
