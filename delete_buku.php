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

if (isset($_GET['id'])) {
    $buku->id_buku = $_GET['id'];

    if ($buku->deleteBuku()) {
        header("Location: data_buku.php");
    } else {
        echo "Error: Could not delete the data.";
    }
}
?>
