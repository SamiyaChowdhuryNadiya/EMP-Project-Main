<?php
session_start();
include "connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

$instructors = $conn->query("SELECT * FROM instructor");
?>

<!DOCTYPE html>
<html>
<head>
<title>Instructor List</title>

<style>
body{
    font-family:Arial;
    background:#f4f7fb;
    margin:0;
}

.container{
    width:90%;
    margin:auto;
    margin-top:30px;
    background:white;
    padding:20px;
    border-radius:10px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}

th{
    background:#0f172a;
    color:white;
}

.btn{
    padding:6px 10px;
    background:#6366f1;
    color:white;
    text-decoration:none;
    border-radius:5px;
}
</style>

</head>

<body>

<div class="container">

<h2>👨‍🏫 Instructor List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Designation</th>
    <th>Action</th>
</tr>

<?php while($row = $instructors->fetch_assoc()){ ?>

<tr>
    <td><?= $row['instructorID'] ?></td>
    <td><?= $row['firstname']." ".$row['lastname'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td><?= $row['designation'] ?></td>
    <td>
        <a class="btn" href="instructor_view.php?id=<?= $row['instructorID'] ?>">
            View
        </a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>