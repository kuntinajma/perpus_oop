<?php
session_start();

// Include database and user class
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Redirect user to login page if not logged in
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$database = new Database();
$db = $database->getConnection();

// Initialize User object
$user = new User($db);

// Fetch user data based on session email
$email = $_SESSION['login_user'];
$userData = $user->getUserByEmail($email);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate current password
    if ($currentPassword == $userData['password']) {
        // Validate new password
        if ($newPassword === $confirmPassword) {
            // Change password
            if ($user->changePassword($newPassword, $userData['id'])) {
                // Password changed successfully
                // alert berhasil
            } else {
                //alert gagal
            }
        } else {
            // echo '<div class="alert alert-danger" role="alert">New password and confirm password do not match.</div>';
            // alert pw dan konfirm tidak sama
        }
    } else {
        // echo '<div class="alert alert-danger" role="alert">Current password is incorrect.</div>';
        // alert pw dulu salah
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi</title>
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
        <div class="card m-3 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h2>Ganti Kata Sandi Akun E-Library</h2>
                        <form method="POST">
                            <div class="form-group">
                                <label for="current_password">Kata Sandi Lama:</label>
                                <input type="password" class="form-control" id="current_password"
                                    name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Kata Sandi Baru:</label>
                                <input type="password" class="form-control" id="new_password" name="new_password"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Konfirmasi Kata Sandi Baru:</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ubah Kata Sandi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>