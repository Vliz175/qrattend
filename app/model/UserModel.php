<?php
require_once __DIR__ . '/../config/Database.php';
class UserModel
{

    private $database;
    private $db;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->connect();
    }

    //* get all users 
    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* create new user
    public function createUser($name, $email, $phone_number, $birthDate, $password, $gender)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, phone_number, birth_date, password, gender) VALUES (:name, :email, :phone_number, :birth_date, :password, :gender)");
        $stmt->bindParam(':name', $name);
        $stmt->bindparam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':birth_date', $birthDate);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':gender', $gender);
        return $stmt->execute();
    }

    //* update user by id
    public function updateUser($id, $img = null, $password = null, $name = null)
    {
        $fields = [];
        $params = [':id' => $id];

        if ($img !== null) {
            $fields[] = "user_image = :user_image";
            $params[':user_image'] = $img;
        }

        if ($password !== null) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $fields[] = "password = :password";
            $params[':password'] = $hashedPassword;
        }

        if ($name !== null) {
            $fields[] = "name = :name";
            $params[':name'] = $name;
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }



    //* delete user by id
    public function deleteUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    //* get user by id
    public function getUserById($user_id)
    {
        $sql = "SELECT 
                u.user_id,
                u.name,
                u.email,
                u.birth_date,
                u.gender,
                u.phone_number,
                u.password,
                u.user_image,
                u.role,
                uv.verification_id
            FROM users u
            LEFT JOIN user_verifications uv ON u.user_id = uv.user_id
            WHERE u.user_id = :user_id;
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //* find user by email
    public function findUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //* verify user password
    public function verifyPassword($email, $password)
    {
        $user = $this->findUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }

    public function createVerification($userId, $fullName, $nik, $pathFotoWajah, $pathFotoKtp)
    {
        $stmt = $this->db->prepare("
        INSERT INTO user_verifications 
        (user_id, full_name, nik, selfie_photo, id_card_photo)
        VALUES (:user_id, :full_name, :nik, :selfie_photo, :id_card_photo)
    ");

        return $stmt->execute([
            'user_id' => $userId,
            'full_name'  => $fullName,
            'nik'        => $nik,
            'selfie_photo' => $pathFotoWajah,
            'id_card_photo'   => $pathFotoKtp
        ]);
    }

    public function getUserVerification(string $status = 'pending')
    {
        $sql = "SELECT * FROM user_verifications
            WHERE verification_status = :status";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'status' => $status
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateVerificationStatus($verificationId, $status)
    {
        $sql = "UPDATE user_verifications 
                SET verification_status = :status, verified_at = NOW()
                WHERE verification_id = :verificationId";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':verificationId' => $verificationId,
            ':status' => $status
        ]);
    }
}
