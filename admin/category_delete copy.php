<?php
require '../config/config.php';
require '../config/common.php';
session_start();
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
}   
if ($_SESSION['role'] != 1) {
    header('Location: login.php');
}   
$stmt= $pdo->prepare("DELETE FROM categories WHERE id=:id");
$stmt->execute([':id' => $_GET['id']]);
header('Location: category.php');
?>