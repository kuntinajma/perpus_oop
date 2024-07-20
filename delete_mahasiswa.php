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

if (isset($_GET['id'])) {
    $mahasiswa->id = $_GET['id'];

    if ($mahasiswa->deleteMahasiswa()) {
        header("Location: data_mahasiswa.php");
    } else {
        echo "Error: Could not delete the data.";
    }
}
?>
