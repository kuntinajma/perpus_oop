<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Peminjaman.php';
require_once 'classes/Pengembalian.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$query_buku = "SELECT id_buku, judul FROM buku WHERE status = 'dipinjam'";
$stmt_buku = $db->prepare($query_buku);
$stmt_buku->execute();
$daftar_buku = $stmt_buku->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
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
        $query_2 = "SELECT id_peminjaman FROM peminjam WHERE id_mahasiswa = :id_mahasiswa AND id_buku = :id_buku AND denda is null";
        $stmt2 = $db->prepare($query_2);
        $stmt2->bindParam(':id_mahasiswa', $id_mahasiswa);
        $stmt2->bindParam(':id_buku', $_POST['id_buku']);
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $id_peminjaman = $row2['id_peminjaman'];
    $pengembalian = new Pengembalian($db);
    $pengembalian->id_peminjaman = $id_peminjaman;
    $pengembalian->id_buku = $_POST['id_buku'];
    $pengembalian->tanggal_kembali = date('Y-m-d');

    if ($pengembalian->createPengembalian()) {
        // Update status buku menjadi tersedia
        $query_update_buku = "UPDATE buku SET status='tersedia' WHERE id_buku=:id_buku";
        $stmt_update_buku = $db->prepare($query_update_buku);
        $stmt_update_buku->bindParam(":id_buku", $_POST['id_buku']);
        $stmt_update_buku->execute();

        header("Location: pengembalian.php");
    } else {
        echo "Error: Could not process the return.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Pengembalian</title>
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
        <h2 class="mt-5 mx-5">Data Pengembalian</h2>
        <div class="card mx-5">
            <div class="card-header text-center">
                <h2>Tambah Pengembalian</h2>
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
                    <?php foreach ($daftar_buku as $buku) : ?>
                        <option value="<?php echo $buku['id_buku']; ?>"><?php echo $buku['judul']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_pengembalian">Tanggal Pengembalian:</label>
                <input type="text" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
            </div>
        </div>
    </div>
</body>
</html>


