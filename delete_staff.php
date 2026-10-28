<?php
session_start();
include "connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

/* STEP 1: DELETE FROM CHILD TABLE */
$stmt1 = $conn->prepare("DELETE FROM staff_workschedule WHERE assigned_to=?");
$stmt1->bind_param("s", $id);
$stmt1->execute();

/* STEP 2: DELETE FROM PARENT */
$stmt2 = $conn->prepare("DELETE FROM office_staff WHERE staffID=?");
$stmt2->bind_param("s", $id);
$stmt2->execute();

header("Location: officestaff_list.php");
exit();
?>
