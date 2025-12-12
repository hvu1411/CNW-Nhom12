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
    
    /**
     * Cập nhật avatar
     */
    public function cậpNhậtAvatar($id, $avatar_filename)
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET avatar = :avatar 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':avatar', $avatar_filename);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Đổi mật khẩu
     */
    public function đổiMậtKhẩu($id, $mật_khẩu_mới)
    {
        $mật_khẩu_đã_mã_hóa = password_hash($mật_khẩu_mới, PASSWORD_BCRYPT);
        
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET password = :password 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':password', $mật_khẩu_đã_mã_hóa);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function kiểmTraEmailTồnTại($email)
    {
        $câu_truy_vấn = "SELECT id FROM " . $this->tên_bảng . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Kiểm tra username đã tồn tại chưa
     */
    public function kiểmTraUsernameTồnTại($username)
    {
        $câu_truy_vấn = "SELECT id FROM " . $this->tên_bảng . " WHERE username = :username LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Cập nhật thông tin profile
     */
    public function cậpNhậtProfile($id, $fullname, $email, $phone)
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET fullname = :fullname, email = :email, phone = :phone 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Lấy user theo email (không phải admin)
     */
    public function lấyTheoEmail($email)
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " 
                        WHERE email = :email AND role != 2 
                        LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Lưu token reset password
     */
    public function lưuTokenResetPassword($user_id, $token, $expiry)
    {
        // Xóa token cũ nếu có
        $câu_xóa = "DELETE FROM password_resets WHERE user_id = :user_id";
        $stmt = $this->kết_nối->prepare($câu_xóa);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        // Lưu token mới
        $câu_truy_vấn = "INSERT INTO password_resets (user_id, token, expiry) 
                        VALUES (:user_id, :token, :expiry)";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiry);
        
        return $stmt->execute();
    }
    
    /**
     * Kiểm tra token reset password
     */
    public function kiểmTraTokenResetPassword($token)
    {
        $câu_truy_vấn = "SELECT pr.*, u.email, u.username, u.role 
                        FROM password_resets pr 
                        JOIN users u ON pr.user_id = u.id 
                        WHERE pr.token = :token AND pr.expiry > NOW() AND u.role != 2
                        LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Xóa token reset password
     */
    public function xóaTokenResetPassword($user_id)
    {
        $câu_truy_vấn = "DELETE FROM password_resets WHERE user_id = :user_id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
}
