<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $nama;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    function register() {
        $query = "INSERT INTO " . $this->table_name . " (email, password, nama) VALUES (:email, :password, :nama)";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->nama = htmlspecialchars(strip_tags($this->nama));

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':nama', $this->nama);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function login() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND password = :password";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
    function getUserByEmail($email) {
        $query = "SELECT id, nama, email, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row;
        }

        return null;
    }

    // Mengubah kata sandi pengguna
    function changePassword($newPassword, $userId) {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Menghapus akun pengguna
    function deleteAccount($userId) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
