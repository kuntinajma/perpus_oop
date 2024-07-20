<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Peminjaman.php';
require_once 'classes/Buku.php'; // Tambahkan kelas Buku untuk mengambil data buku

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$buku = new Buku($db);
$daftar_buku = $buku->getAllBuku(); 

if ($_POST) {
    if ($_POST && isset($_POST['nim'])) {
        $nim = $_POST['nim'];
        $query = "SELECT id FROM mahasiswa WHERE nim = :nim";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_mahasiswa = $row['id'];
        if($id_mahasiswa == NULL){
            header("Location: create_peminjaman.php");
        }
    }
    $peminjaman = new Peminjaman($db);
    $peminjaman->id_buku = $_POST['id_buku'];
    $peminjaman->id_mahasiswa = $id_mahasiswa;
    $peminjaman->tanggal_pinjam = $_POST['tanggal_pinjam'];
    $peminjaman->tanggal_kembali = date('Y-m-d', strtotime('+5 days', strtotime($peminjaman->tanggal_pinjam)));

    if ($peminjaman->createPeminjaman()) {
        $query = "UPDATE buku SET status='dipinjam' WHERE id_buku=:id_buku";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id_buku", $_POST['id_buku']);
        $stmt->execute();

        header("Location: peminjaman.php");
    } else {
        echo "Error: Could not save the data.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Peminjaman</title>
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
        <h2 class="mt-5 mx-5">Data Peminjaman</h2>
        <div class="card mx-5">
            <div class="card-header text-center">
                <h2>Tambah Peminjaman</h2>
            </div>
            <div class="card-body">
            <form method="POST">
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" class="form-control" id="nim" name="nim" required>
            </div>
            <div class="form-group">
                <label for="id_buku">Judul Buku:</label>
                <select class="form-control" id="id_buku" name="id_buku" required>
                    <option value="">Pilih Judul Buku</option>
                    <?php foreach ($daftar_buku as $buku) : ?>
                        <option value="<?php echo $buku['id_buku']; ?>"><?php echo $buku['judul']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_kembali">Tanggal Pinjam:</label>
                <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.sidebar a[href="create_peminjaman.php"]').classList.add('active');
    </script>
</body>
</html>


