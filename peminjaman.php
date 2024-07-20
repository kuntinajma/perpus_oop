<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Peminjaman.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$peminjaman = new Peminjaman($db);
$listPeminjaman = $peminjaman->getAllPeminjaman();

// Handle search
$listPeminjaman = [];
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $listPeminjaman = $peminjaman->searchPeminjaman($keyword);
} else {
    $listPeminjaman = $peminjaman->getAllPeminjaman();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman</title>
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
        .logout {
            text-align: right;
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
        <h2>Data Peminjaman</h2>
        <button class="btn btn-primary mb-3"><a href="create_peminjaman.php" style="color: white;">Pinjam Buku</a></button>
        <form action="peminjaman.php" method="GET" class="mb-3">
    <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <button type="submit" class="btn btn-primary mt-2">Cari</button>
</form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Kode Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listPeminjaman as $index => $peminjaman) : ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $peminjaman['nim']; ?></td>
                        <td><?php echo $peminjaman['nama']; ?></td>
                        <td><?php echo $peminjaman['id_buku']; ?></td>
                        <td><?php echo $peminjaman['tanggal_pinjam']; ?></td>
                        <td><?php echo $peminjaman['tanggal_kembali']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.querySelector('.sidebar a[href="peminjaman.php"]').classList.add('active');
    </script>
</body>
</html>
