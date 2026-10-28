<?php
session_start();
include "connection.php";

/* ONLY ADMIN ACCESS */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* FETCH LATEST FIRST */
$result = $conn->query("SELECT * FROM contact ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Messages</title>

<style>
body{
    font-family: Arial;
    background:#f4f7fb;
    padding:20px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

h2{
    margin:0;
}

.back-btn{
    background:#6b7280;
    color:white;
    padding:8px 14px;
    text-decoration:none;
    border-radius:6px;
}

.back-btn:hover{
    background:#4b5563;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th, td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#0f172a;
    color:white;
}

tr:hover{
    background:#f1f1f1;
}

.delete-btn{
    background:red;
    color:white;
    padding:6px 12px;
    text-decoration:none;
    border-radius:5px;
}

.delete-btn:hover{
    background:darkred;
}
</style>

</head>

<body>

<!-- HEADER WITH BACK BUTTON -->
<div class="header">

    <h2>Contact Messages (Admin Only)</h2>

    <!-- BACK BUTTON -->
    <a href="admin_dashboard.php" class="back-btn">
        ← Back
    </a>

</div>

<table>

<tr>
<th>SL</th>
<th>Name</th>
<th>Email</th>
<th>Message</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php
$sl = 1;

while($row = $result->fetch_assoc()){
?>

<tr>
<td><?= $sl++ ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['message'] ?></td>
<td><?= $row['created_at'] ?? 'N/A' ?></td>

<td>
<a class="delete-btn" href="delete_message.php?id=<?= $row['id'] ?>" 
onclick="return confirm('Delete this message?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>
