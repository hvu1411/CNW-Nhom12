<?php
class Lesson {
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    private function sanitizeText($text)
    {
        return trim($text);
    }
    public function getLessonsByCourse($courseId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM lessons WHERE course_id = ? ORDER BY `order` ASC"
        );
        $stmt->execute([(int)$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getLesson($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createLesson($data)
    {
        $title     = $this->sanitizeText($data['title']);
        $content   = $data['content'];
        $videoUrl  = $this->sanitizeText($data['video_url']);
        $order     = (int)$data['order'];

        $stmt = $this->db->prepare("
            INSERT INTO lessons (course_id, title, content, video_url, `order`, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        return $stmt->execute([
            (int)$data['course_id'], 
            $title,
            $content,
            $videoUrl,
            $order
        ]);
    }

    public function updateLesson($id, $data)
    {
        $title    = $this->sanitizeText($data['title']);
        $content  = $data['content'];
        $videoUrl = $this->sanitizeText($data['video_url']);
        $order    = (int)$data['order'];

        $stmt = $this->db->prepare("
            UPDATE lessons 
            SET title = ?, 
                content = ?, 
                video_url = ?, 
                `order` = ?
            WHERE id = ? AND course_id = ?
        ");

        return $stmt->execute([
            $title,
            $content,
            $videoUrl,
            $order,
            (int)$id,
            (int)$data['course_id'] 
        ]);
    }

    public function deleteLesson($id, $courseId)
    {
        $stmt = $this->db->prepare("DELETE FROM lessons WHERE id = ? AND course_id = ?");
        return $stmt->execute([(int)$id, (int)$courseId]);
    }
}
