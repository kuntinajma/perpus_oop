<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Buku.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$buku = new Buku($db);
$listBuku = $buku->getAllBuku();

// Proses pencarian
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $listBuku = $buku->searchBuku($keyword); // Memanggil method pencarian
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        <h3>Data Buku</h3>
        <button class="btn btn-primary mb-3"><a href="create_buku.php" style="color: white;">Tambah Data</a></button>
        <form method="GET" class="mb-3">
            <input type="text" class="form-control" placeholder="Search" name="search">
            <button type="submit" class="btn btn-primary mt-2">Cari</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun Terbit</th>
                    <th>Status</th>
                    <th>Kelola</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listBuku as $index => $buku): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $buku['judul']; ?></td>
                        <td><?php echo $buku['penulis']; ?></td>
                        <td><?php echo $buku['tahun_terbit']; ?></td>
                        <td><?php echo $buku['status']; ?></td>
                        <td>
                            <a href="update_buku.php?id=<?php echo $buku['id_buku']; ?>"><i class='fas fa-edit'></i></a> |
                            <a href="delete_buku.php?id=<?php echo $buku['id_buku']; ?>"><i class='fas fa-trash'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.querySelector('.sidebar a[href="data_buku.php"]').classList.add('active');
    </script>
</body>

</html>