<?php
class Peminjaman {
    private $conn;
    private $table_name = "peminjam";
    public $id_peminjaman;
    public $id_mahasiswa;
    public $id_buku;
    public $tanggal_pinjam;
    public $tanggal_kembali;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllPeminjaman() {
        $query = "SELECT p.id_peminjaman, m.nim, m.nama, b.id_buku, b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.denda
                  FROM " . $this->table_name . " p
                  LEFT JOIN mahasiswa m ON p.id_mahasiswa = m.id
                  LEFT JOIN buku b ON p.id_buku = b.id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeminjamanById($id_peminjaman) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_peminjaman);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPeminjaman() {
        $query = "INSERT INTO " . $this->table_name . " SET id_mahasiswa=:id_mahasiswa, id_buku=:id_buku, tanggal_pinjam=:tanggal_pinjam, tanggal_kembali=:tanggal_kembali";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_mahasiswa", $this->id_mahasiswa);
        $stmt->bindParam(":id_buku", $this->id_buku);
        $stmt->bindParam(":tanggal_pinjam", $this->tanggal_pinjam);
        $stmt->bindParam(":tanggal_kembali", $this->tanggal_kembali);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updatePeminjaman() {
        $query = "UPDATE " . $this->table_name . " SET id_mahasiswa=:id_mahasiswa, id_buku=:id_buku, tanggal_pinjam=:tanggal_pinjam, tanggal_kembali=:tanggal_kembali, status=:status WHERE id_peminjaman=:id_peminjaman";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_mahasiswa", $this->id_mahasiswa);
        $stmt->bindParam(":id_buku", $this->id_buku);
        $stmt->bindParam(":tanggal_pinjam", $this->tanggal_pinjam);
        $stmt->bindParam(":tanggal_kembali", $this->tanggal_kembali);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id_peminjaman", $this->id_peminjaman);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deletePeminjaman() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_peminjaman = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_peminjaman);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function searchPeminjaman($keyword) {
        $query = "SELECT peminjam.*, mahasiswa.nama, mahasiswa.*, buku.judul
                  FROM peminjam
                  INNER JOIN mahasiswa ON peminjam.id_mahasiswa = mahasiswa.id
                  INNER JOIN buku ON peminjam.id_buku = buku.id_buku
                  WHERE mahasiswa.nim LIKE :keyword
                     OR mahasiswa.nama LIKE :keyword
                     OR peminjam.tanggal_pinjam LIKE :keyword
                     OR peminjam.tanggal_kembali LIKE :keyword
                     OR buku.judul LIKE :keyword
                     OR peminjam.id_buku LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $keyword = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
