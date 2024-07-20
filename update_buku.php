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

if ($_POST) {
    $buku->id_buku = $_POST['id_buku'];
    $buku->judul = $_POST['judul'];
    $buku->penulis = $_POST['penulis'];
    $buku->tahun_terbit = $_POST['tahun_terbit'];
    $buku->status = $_POST['status'];

    if ($buku->updateBuku()) {
        header("Location: data_buku.php");
    } else {
        echo "Error: Could not update the data.";
    }
} else {
    $bukuData = $buku->getBukuById($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Buku</title>
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
        <h2 class="mt-5 mx-5">Data Buku</h2>
        <div class="card mx-5">
            <div class="card-header text-center">
                <h2>Update Data Buku</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id_buku" value="<?php echo $bukuData['id_buku']; ?>">
                    <div class="form-group">
                        <label for="judul">Judul:</label>
                        <input type="text" class="form-control" id="judul" name="judul"
                            value="<?php echo $bukuData['judul']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="penulis">Penulis:</label>
                        <input type="text" class="form-control" id="penulis" name="penulis"
                            value="<?php echo $bukuData['penulis']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit:</label>
                        <input type="number" class="form-control" min="1900" max="2099" step="1"
                            value="<?php echo $bukuData['tahun_terbit']; ?>" id="tahun_terbit" name="tahun_terbit"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="tersedia" <?php echo ($bukuData['status'] == 'tersedia') ? 'selected' : ''; ?>>
                                Tersedia</option>
                            <option value="dipinjam" <?php echo ($bukuData['status'] == 'dipinjam') ? 'selected' : ''; ?>>
                                Dipinjam</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.sidebar a[href="update_buku.php"]').classList.add('active');
    </script>
</body>

</html>