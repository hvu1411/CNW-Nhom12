<?php
/**
 * Model Lesson - Quản lý bài học
 */
class Lesson
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $tên_bảng = 'lessons';
    
    // Thuộc tính của lesson
    public $id;
    public $course_id;
    public $title;
    public $content;
    public $video_url;
    public $image;
    public $order;
    public $created_at;
    
    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->kết_nối = $db;
    }
    
    /**
     * Tạo bài học mới
     */
    public function tạo()
    {
        $câu_truy_vấn = "INSERT INTO " . $this->tên_bảng . " 
                        (course_id, title, content, video_url, image, `order`) 
                        VALUES (:course_id, :title, :content, :video_url, :image, :order)";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':video_url', $this->video_url);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':order', $this->order);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Lấy bài học theo khóa học
     */
    public function lấyTheoKhóaHọc($course_id)
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " 
                        WHERE course_id = :course_id 
                        ORDER BY `order` ASC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy bài học theo ID
     */
    public function lấyTheoId($id)
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Cập nhật bài học
     */
    public function cậpNhật()
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET title = :title, content = :content, video_url = :video_url, image = :image, `order` = :order 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':video_url', $this->video_url);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':order', $this->order);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Cập nhật chỉ ảnh bài học
     */
    public function cậpNhậtẢnh($id, $image)
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " SET image = :image WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Xóa bài học
     */
    public function xóa($id)
    {
        $câu_truy_vấn = "DELETE FROM " . $this->tên_bảng . " WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
