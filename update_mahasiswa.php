<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Mahasiswa.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$mahasiswa = new Mahasiswa($db);

if ($_POST) {
    $mahasiswa->id = $_POST['id'];
    $mahasiswa->nim = $_POST['nim'];
    $mahasiswa->nama = $_POST['nama'];
    $mahasiswa->prodi = $_POST['prodi'];

    if ($mahasiswa->updateMahasiswa()) {
        header("Location: data_mahasiswa.php");
    } else {
        echo "Error: Could not update the data.";
    }
} else {
    $mahasiswaData = $mahasiswa->getMahasiswaById($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Mahasiswa</title>
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
        <h2 class="mt-5 mx-5">Data Mahasiswa</h2>
        <div class="card mx-5">
            <div class="card-header text-center">
                <h2>Update Data Mahasiswa</h2>
            </div>
            <div class="card-body">
            <form method="POST">
            <input type="hidden" name="id" value="<?php echo $mahasiswaData['id']; ?>">
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $mahasiswaData['nim']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $mahasiswaData['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="prodi">Program Studi:</label>
                <input type="text" class="form-control" id="prodi" name="prodi" value="<?php echo $mahasiswaData['prodi']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
            </div>
        </div>
    </div>
    <script>
    document.querySelector('.sidebar a[href="data_mahasiswa.php"]').classList.add('active');
</script>

</body>
</html>


