<?php
session_start();
require_once 'classes/Database.php'; // Sesuaikan dengan lokasi file Database.php
require_once 'classes/User.php'; // Sesuaikan dengan lokasi file User.php

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$userData = $user->getUserByEmail($_SESSION['login_user']);

if (!$userData) {
    echo "User not found.";
    exit;
}

// Jika tombol ubah kata sandi diklik
if (isset($_POST['change_password'])) {
    // Redirect ke halaman ubah kata sandi
    header("Location: change_password.php");
    exit;
}

// Jika tombol hapus akun diklik
if (isset($_POST['delete_account'])) {
    // Proses hapus akun (harus diimplementasikan sesuai logika aplikasi)
    // Contoh: $user->deleteAccount();
    // Setelah dihapus, redirect ke halaman logout atau halaman lainnya
    header("Location: logout.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
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
        <div class="card m-5 bg-light">
            <div class="card-body">
            <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center mb-4">Profil Akun E-Library</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" class="form-control" id="nama" value="<?php echo $userData['nama']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $userData['email']; ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="change_password">Ubah Kata Sandi</button>
                </form>
                <!-- <button type="submit" class="btn btn-danger w-100 mt-2" name="delete_account" onclick="return confirm('Apakah Anda yakin ingin menghapus akun?')">Hapus Akun</button> -->
            </div>
        </div>
            </div>
        </div>
    </div>
</body>
</html>
