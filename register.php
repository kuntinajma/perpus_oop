<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($db);
    $user->email = $_POST['email'];
    $user->nama = $_POST['nama'];
    $user->password = $_POST['password'];

    if ($user->register()) {
        header("location: login.php");
    } else {
        $error = "Error: Could not register";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 600px; /* Atur lebar kartu sesuai kebutuhan */
            max-width: 450px; /* Lebar maksimum kartu */
            padding: 20px; /* Padding untuk ruang di dalam kartu */
        }
    </style>
</head>
<body>
    <div class="card m-3 bg-light">
    <div class="card-body">
    <div class="container">
        <div class="login-form">
            <h3>Daftar</h3>
            <form action="" method="post">
            <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama anda" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email anda" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password anda" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                <div class="text-center">
                    <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
                </div>
            </form>
            <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        </div>
    </div>
    </div>
    </div>
</body>
</html>
