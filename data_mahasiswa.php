<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Mahasiswa.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$mahasiswa = new Mahasiswa($db);

// Handle search
$listMahasiswa = [];
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $listMahasiswa = $mahasiswa->searchMahasiswaByAllColumns($keyword);
} else {
    $listMahasiswa = $mahasiswa->getAllMahasiswa();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
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
        <h3>Data Mahasiswa</h3>
        <button class="btn btn-primary mb-3"><a href="create_mahasiswa.php" style="color: white;">Tambah Data</a></button>
        <form action="data_mahasiswa.php" method="GET" class="mb-3">
            <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" class="btn btn-primary mt-2">Cari</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Kelola</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listMahasiswa as $index => $mhs) : ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $mhs['nim']; ?></td>
                        <td><?php echo $mhs['nama']; ?></td>
                        <td><?php echo $mhs['prodi']; ?></td>
                        <td>
                            <a href="update_mahasiswa.php?id=<?php echo $mhs['id']; ?>"><i class='fas fa-edit'></i></a> |
                            <a href="delete_mahasiswa.php?id=<?php echo $mhs['id']; ?>"><i class='fas fa-trash'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
    document.querySelector('.sidebar a[href="data_mahasiswa.php"]').classList.add('active');
</script>

</body>
</html>
