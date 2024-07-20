<?php
class Pengembalian {
    private $conn;
    private $table_name = "peminjam";

    public $id_peminjaman;
    public $id_mahasiswa;
    public $id_buku;
    public $judul_buku;
    public $tanggal_kembali;
    public $denda;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createPengembalian() {
        // Query untuk mencari data peminjaman berdasarkan id_mahasiswa dan judul_buku
        $query = "SELECT id_peminjaman, tanggal_kembali FROM " . $this->table_name . " WHERE id_peminjaman = :id_peminjaman";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_peminjaman', $this->id_peminjaman);
        $stmt->execute();

        // Ambil hasil query
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id_peminjaman = $row['id_peminjaman'];
            $tanggal_kembali = $row['tanggal_kembali'];

            // Hitung denda
            $denda = $this->hitungDenda($tanggal_kembali);

            // Update data peminjaman dengan informasi pengembalian
            $query_update = "UPDATE " . $this->table_name . " SET tanggal_kembali = :tanggal_pengembalian, denda = :denda WHERE id_peminjaman = :id_peminjaman";
            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->bindParam(':tanggal_pengembalian', $this->tanggal_kembali);
            $stmt_update->bindParam(':denda', $denda);
            $stmt_update->bindParam(':id_peminjaman', $this->id_peminjaman);

            if ($stmt_update->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false; // Data peminjaman tidak ditemukan
        }
    }

    private function hitungDenda($tanggal_kembali) {
        $tanggal_sekarang = date('Y-m-d');
        $selisih_hari = strtotime($tanggal_sekarang) - strtotime($tanggal_kembali);
        $denda = max(0, floor($selisih_hari / (60 * 60 * 24)) * 1000); // Denda per hari 1000

        return $denda;
    }
}
?>
