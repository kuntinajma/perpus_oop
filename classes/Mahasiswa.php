<?php
class Mahasiswa {
    private $conn;
    private $table_name = "mahasiswa";

    public $id;
    public $nim;
    public $nama;
    public $prodi;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllMahasiswa() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMahasiswaById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createMahasiswa() {
        $query = "INSERT INTO " . $this->table_name . " SET nim=:nim, nama=:nama, prodi=:prodi";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nim", $this->nim);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":prodi", $this->prodi);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateMahasiswa() {
        $query = "UPDATE " . $this->table_name . " SET nim=:nim, nama=:nama, prodi=:prodi WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nim", $this->nim);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":prodi", $this->prodi);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deleteMahasiswa() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function searchMahasiswaByAllColumns($keyword) {
        $query = "SELECT * FROM mahasiswa WHERE CONCAT_WS(' ', nim, nama, prodi) LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $keyword = '%' . $keyword . '%'; // Tambahkan wildcard (%) di depan dan belakang keyword untuk pencarian fuzzy
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
