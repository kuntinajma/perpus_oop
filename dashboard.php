<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/User.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTotalMahasiswa() {
        return $this->conn->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    }

    public function getTotalBuku() {
        return $this->conn->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    }

    public function getBukuTersedia() {
        return $this->conn->query("SELECT COUNT(*) FROM buku WHERE status = 'tersedia'")->fetchColumn();
    }

    public function getAktivitas() {
        return $this->conn->query("SELECT peminjam.id_peminjaman, peminjam.tanggal_pinjam, peminjam.tanggal_kembali, mahasiswa.nim, mahasiswa.nama, buku.id_buku, buku.judul, IF(peminjam.denda IS NULL, 'Pinjam', 'Kembali') as keterangan
                                    FROM peminjam 
                                    JOIN mahasiswa ON peminjam.id_mahasiswa = mahasiswa.id
                                    JOIN buku ON peminjam.id_buku = buku.id_buku
                                    ORDER BY peminjam.tanggal_pinjam DESC 
                                    LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    }
}

$dashboard = new Dashboard($db);

$totalMahasiswa = $dashboard->getTotalMahasiswa();
$totalBuku = $dashboard->getTotalBuku();
$bukuTersedia = $dashboard->getBukuTersedia();
$aktivitas = $dashboard->getAktivitas();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            height: 100vh;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex: 1;
            padding: 10px;
        }
        .card {
            margin: 10px 0;
        }
        .logout {
            text-align: right;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #343a40;
            color: white;
            padding: 10px;
            margin-left: -10px;
            margin-top: -10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 class="text-center">e-library</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="data_mahasiswa.php">Data Mahasiswa</a>
        <a href="data_buku.php">Data Buku</a>
        <a href="peminjaman.php">Peminjaman</a>
        <a href="pengembalian.php">Pengembalian</a>
        <a href="profil.php">Profil</a>
    </div>
    <div class="content">
        <div class="navbar">
            <span>Selamat datang, <?php echo $_SESSION['login_user']; ?></span>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Mahasiswa</h5>
                        <p class="card-text"><?php echo $totalMahasiswa; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Koleksi Buku</h5>
                        <p class="card-text"><?php echo $totalBuku; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Buku Tersedia</h5>
                        <p class="card-text"><?php echo $bukuTersedia; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <h4>Riwayat Aktivitas</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Datetime</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Id Buku</th>
                    <th>Judul</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aktivitas as $index => $activity) : ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $activity['tanggal_pinjam']; ?></td>
                        <td><?php echo $activity['nim']; ?></td>
                        <td><?php echo $activity['nama']; ?></td>
                        <td><?php echo $activity['id_buku']; ?></td>
                        <td><?php echo $activity['judul']; ?></td>
                        <td><?php echo $activity['keterangan']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
