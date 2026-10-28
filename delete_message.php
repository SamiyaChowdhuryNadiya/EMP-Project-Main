<?php
session_start();
include "connection.php";

/* ADMIN ONLY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM contact WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

header("Location: admin_messages.php");
exit();
?>
