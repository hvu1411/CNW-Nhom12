<?php
/**
 * Model User - Quản lý người dùng
 */
class User
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $tên_bảng = 'users';
    // Cached prepared statements for performance
    private $stmt_đăngKý;
    private $stmt_đăngNhập;
    private $stmt_lấyTheoId;
    private $stmt_lấyTấtCả;
    private $stmt_lấyTheoVaiTrò;
    private $stmt_cậpNhật;
    private $stmt_xóa;
    private $stmt_đếmTấtCả;
    private $stmt_đếmTheoVaiTrò;
    
    // Thuộc tính của user
    public $id;
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $role;
    public $created_at;
    
    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->kết_nối = $db;
        // Prepare commonly used statements once to reduce overhead
        $this->stmt_đăngKý = $this->kết_nối->prepare(
            "INSERT INTO " . $this->tên_bảng . " (username, email, password, fullname, role) VALUES (:username, :email, :password, :fullname, :role)"
        );

        $this->stmt_đăngNhập = $this->kết_nối->prepare(
            "SELECT id, username, email, password, fullname, role, created_at FROM " . $this->tên_bảng . " WHERE username = :username OR email = :email LIMIT 1"
        );

        $this->stmt_lấyTheoId = $this->kết_nối->prepare(
            "SELECT id, username, email, fullname, role, created_at FROM " . $this->tên_bảng . " WHERE id = :id LIMIT 1"
        );

        $this->stmt_lấyTấtCả = $this->kết_nối->prepare(
            "SELECT id, username, email, fullname, role, created_at FROM " . $this->tên_bảng . " ORDER BY created_at DESC"
        );

        $this->stmt_lấyTheoVaiTrò = $this->kết_nối->prepare(
            "SELECT id, username, email, fullname, role, created_at FROM " . $this->tên_bảng . " WHERE role = :role ORDER BY created_at DESC"
        );

        $this->stmt_cậpNhật = $this->kết_nối->prepare(
            "UPDATE " . $this->tên_bảng . " SET email = :email, fullname = :fullname, role = :role WHERE id = :id"
        );

        $this->stmt_xóa = $this->kết_nối->prepare(
            "DELETE FROM " . $this->tên_bảng . " WHERE id = :id"
        );
        // Count statements for efficient aggregates
        $this->stmt_đếmTấtCả = $this->kết_nối->prepare(
            "SELECT COUNT(*) FROM " . $this->tên_bảng
        );

        $this->stmt_đếmTheoVaiTrò = $this->kết_nối->prepare(
            "SELECT COUNT(*) FROM " . $this->tên_bảng . " WHERE role = :role"
        );
    }
    
    /**
     * Đăng ký người dùng mới
     */
    public function đăngKý()
    {
        try {
            $stmt = $this->stmt_đăngKý;
            $mật_khẩu_đã_mã_hóa = password_hash($this->password, PASSWORD_BCRYPT);

            // Use bindValue with explicit types for slight speed improvement
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $mật_khẩu_đã_mã_hóa, PDO::PARAM_STR);
            $stmt->bindValue(':fullname', $this->fullname, PDO::PARAM_STR);
            $stmt->bindValue(':role', $this->role, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Đăng nhập
     */
    public function đăngNhập()
    {
        try {
            $stmt = $this->stmt_đăngNhập;
            // Execute with an array is typically faster than multiple bindParam calls
            $stmt->execute([':username' => $this->username, ':email' => $this->username]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->fullname = $row['fullname'];
                $this->role = $row['role'];
                $this->created_at = $row['created_at'];
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Lấy thông tin user theo ID
     */
    public function lấyTheoId($id)
    {
        $stmt = $this->stmt_lấyTheoId;
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy tất cả người dùng
     */
    public function lấyTấtCả()
    {
        $stmt = $this->stmt_lấyTấtCả;
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng người dùng (efficient COUNT)
     */
    public function đếmTấtCả()
    {
        $stmt = $this->stmt_đếmTấtCả;
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Lấy người dùng theo vai trò
     */
    public function lấyTheoVaiTrò($vai_trò)
    {
        $stmt = $this->stmt_lấyTheoVaiTrò;
        $stmt->execute([':role' => $vai_trò]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm người dùng theo vai trò (efficient COUNT)
     */
    public function đếmTheoVaiTrò($vai_trò)
    {
        $stmt = $this->stmt_đếmTheoVaiTrò;
        $stmt->execute([':role' => $vai_trò]);
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Cập nhật thông tin user
     */
    public function cậpNhật()
    {
        try {
            $stmt = $this->stmt_cậpNhật;
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':fullname', $this->fullname, PDO::PARAM_STR);
            $stmt->bindValue(':role', $this->role, PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$this->id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Xóa user
     */
    public function xóa($id)
    {
        try {
            $stmt = $this->stmt_xóa;
            return $stmt->execute([':id' => (int)$id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
