<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ADD BUILDING */
if (isset($_POST['add'])) {

    $status = "Active";

    $stmt = $conn->prepare("INSERT INTO building_info (building_name, location, capacity, status) VALUES (?, ?, ?, ?)");

    $stmt->bind_param(
        "ssis",
        $_POST['building_name'],
        $_POST['location'],
        $_POST['capacity'],
        $status
    );

    $stmt->execute();
    header("Location: building_info.php");
    exit();
}

/* DELETE BUILDING */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM building_info WHERE building_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: building_info.php");
    exit();
}

/* FETCH DATA */
$result = $conn->query("SELECT * FROM building_info ORDER BY building_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Building Info</title>

<style>
body{
    font-family: Arial;
    background:#f4f7fb;
    padding:20px;
}

.container{
    max-width:900px;
    margin:auto;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

input{
    padding:10px;
    margin:5px;
    width:23%;
}

button{
    padding:10px 15px;
    background:#6366f1;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th,td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#0f172a;
    color:white;
}

.delete{
    background:red;
    color:white;
    padding:5px 10px;
    text-decoration:none;
    border-radius:5px;
}

.back-btn{
    display:inline-block;
    margin-bottom:15px;
    padding:8px 12px;
    background:#6b7280;
    color:white;
    text-decoration:none;
    border-radius:6px;
}
</style>

</head>

<body>

<div class="container">

<!-- BACK BUTTON -->
<a href="admin_dashboard.php" class="back-btn">← Back</a>

<h2>🏢 Building Management</h2>

<!-- ADD FORM -->
<div class="card">

<form method="POST">

<input type="text" name="building_name" placeholder="Building Name" required>

<input type="text" name="location" placeholder="Location">

<input type="number" name="capacity" placeholder="Capacity">

<button type="submit" name="add">Add Building</button>

</form>

</div>

<!-- TABLE -->
<div class="card">

<table>

<tr>
<th>SL</th>
<th>Building Name</th>
<th>Location</th>
<th>Capacity</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php 
$sl = 1;
while($row = $result->fetch_assoc()){ 
?>

<tr>
<td><?= $sl++ ?></td>
<td><?= $row['building_name'] ?></td>
<td><?= $row['location'] ?></td>
<td><?= $row['capacity'] ?></td>
<td><?= $row['status'] ?></td>

<td>
<a class="delete" href="building_info.php?delete=<?= $row['building_id'] ?>" onclick="return confirm('Delete?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>
