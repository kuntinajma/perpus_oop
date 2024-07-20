<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Pengembalian.php';
require_once 'classes/Peminjaman.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$pengembalian = new Peminjaman($db);
$listPengembalian = $pengembalian->getAllPeminjaman();

$query = "SELECT p.id_peminjaman, m.nim, m.nama, b.id_buku, b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.denda
          FROM peminjam p
          INNER JOIN mahasiswa m ON p.id_mahasiswa = m.id
          INNER JOIN buku b ON p.id_buku = b.id_buku";
$stmt = $db->prepare($query);
$stmt->execute();
$hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);

$listPengembalian = [];
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $hasil = $pengembalian->searchPeminjaman($keyword);
} else {
    $hasil = $pengembalian->getAllPeminjaman();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengembalian</title>
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
        <a href="index.php">index</a>
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
        <h2>Data Pengembalian</h2>
        <button class="btn btn-primary mb-3"><a href="create_pengembalian.php" style="color: white;">Kembalikan Buku</a></button>
        <form action="pengembalian.php" method="GET" class="mb-3">
    <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <button type="submit" class="btn btn-primary mt-2">Cari</button>
</form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">NIM</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Kode Buku</th>
                    <th scope="col">Judul</th>
                    <th scope="col">Tanggal Pengembalian</th>
                    <th scope="col">Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hasil as $index => $data) : ?>
                    <?php
                        if($data['denda'] != ""){
                            ?>
                            <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo $data['nim']; ?></td>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['id_buku']; ?></td>
                        <td><?php echo $data['judul']; ?></td>
                        <td><?php echo $data['tanggal_kembali']; ?></td>
                        <td><?php echo $data['denda']; ?></td>
                    </tr>
                            <?php
                        }
                        ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
