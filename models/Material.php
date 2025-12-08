<?php
class Material {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createMaterial($lessonId, $filename, $filePath, $fileType)
    {
        $stmt = $this->db->prepare("
            INSERT INTO materials (lesson_id, filename, file_path, file_type, uploaded_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        return $stmt->execute([
            (int)$lessonId, 
            $filename,
            $filePath,
            $fileType
        ]);
    }

    public function getMaterialsByLesson($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM materials
            WHERE lesson_id = ?
            ORDER BY uploaded_at DESC
        ");

        $stmt->execute([(int)$lessonId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteMaterial($id)
    {
        $stmt = $this->db->prepare("DELETE FROM materials WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
}
