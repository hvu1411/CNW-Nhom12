<?php
/**
 * Model Category - Quản lý danh mục khóa học
 */
class Category
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $tên_bảng = 'categories';
    
    // Thuộc tính của category
    public $id;
    public $name;
    public $description;
    public $created_at;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->kết_nối = $db;
    }

    /**
     * Tạo danh mục mới
     */
    public function tạo()
    {
        $câu_truy_vấn = "INSERT INTO {$this->tên_bảng} (name, description) VALUES (:name, :description)";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Lấy tất cả danh mục
     */
    public function lấyTấtCả()
    {
        $câu_truy_vấn = "SELECT id, name, description, created_at FROM {$this->tên_bảng} ORDER BY name ASC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh mục theo ID
     */
    public function lấyTheoId($id)
    {
        $câu_truy_vấn = "SELECT id, name, description, created_at FROM {$this->tên_bảng} WHERE id = :id LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật danh mục
     */
    public function cậpNhật()
    {
        $câu_truy_vấn = "UPDATE {$this->tên_bảng} 
                        SET name = :name, description = :description 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Xóa danh mục
     */
    public function xóa($id)
    {
        $câu_truy_vấn = "DELETE FROM {$this->tên_bảng} WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
