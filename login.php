<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($db);
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];

    if ($user->login()) {
        $_SESSION['login_user'] = $user->email;
        $_SESSION['email'] = $user->email;
        header("location: dashboard.php");
    } else {
        $error = "Email atau Password tidak valid";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h3>Masuk</h3>
            <form action="" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email anda" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password anda" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                <div class="text-center">
                    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
                </div>
            </form>
            <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        </div>
    </div>
    </div>
   </div>
</body>
</html>
