<?php
class Buku {
    private $conn;
    private $table_name = "buku";

    public $id_buku;
    public $judul;
    public $penulis;
    public $tahun_terbit;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBuku() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBukuById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_buku = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
public function searchBuku($keyword) {
        $query = "SELECT * FROM buku WHERE CONCAT_WS(' ', judul, penulis, tahun_terbit, status) LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $keyword = '%' . $keyword . '%'; // Tambahkan wildcard (%) di depan dan belakang keyword untuk pencarian fuzzy
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createBuku() {
        $query = "INSERT INTO " . $this->table_name . " (judul, penulis, tahun_terbit, status) VALUES (:judul, :penulis, :tahun_terbit, :status)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":judul", $this->judul);
        $stmt->bindParam(":penulis", $this->penulis);
        $stmt->bindParam(":tahun_terbit", $this->tahun_terbit);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateBuku() {
        $query = "UPDATE " . $this->table_name . " SET judul=:judul, penulis=:penulis, tahun_terbit=:tahun_terbit, status=:status WHERE id_buku=:id_buku";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":judul", $this->judul);
        $stmt->bindParam(":penulis", $this->penulis);
        $stmt->bindParam(":tahun_terbit", $this->tahun_terbit);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id_buku", $this->id_buku);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deleteBuku() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_buku = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_buku);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
