<?php
/**
 * Model Enrollment - Quản lý đăng ký khóa học
 */
class Enrollment
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $db;
    private $tên_bảng = 'enrollments';
    
    // Thuộc tính của enrollment
    public $id;
    public $course_id;
    public $student_id;
    public $enrolled_date;
    public $status;
    public $progress;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->kết_nối = $db;
    }

    /**
     * Kiểm tra học viên đã đăng ký khóa học hay chưa
     */
    public function kiểmTraĐãĐăngKý($courseId, $studentId)
    {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM enrollments WHERE course_id = ? AND student_id = ? LIMIT 1"
        );
        $stmt->execute([(int)$courseId, (int)$studentId]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Tạo bản ghi đăng ký (dùng các thuộc tính đã set)
     */
    public function đăngKý()
    {
        $stmt = $this->db->prepare(
            "INSERT INTO enrollments (course_id, student_id, enrolled_date, status, progress) VALUES (?, ?, NOW(), ?, ?)"
        );

        return $stmt->execute([
            (int)$this->course_id,
            (int)$this->student_id,
            (string)($this->status ?? 'active'),
            (int)($this->progress ?? 0)
        ]);
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
