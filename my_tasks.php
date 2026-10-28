<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'office_staff') {
    header("Location: login.php");
    exit();
}

/* CURRENT STAFF ID */
$staff_id = $_SESSION['user_id'];

/* FETCH ONLY MY TASKS */
$stmt = $conn->prepare("
    SELECT * FROM tasks 
    WHERE assigned_to=? 
    ORDER BY taskID DESC
");

$stmt->bind_param("s", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Tasks</title>

<style>
body{
    font-family: Arial;
    background:#f4f7fb;
    padding:20px;
}

.box{
    background:white;
    padding:20px;
    max-width:800px;
    margin:auto;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
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

.pending{
    color:orange;
    font-weight:bold;
}

.completed{
    color:green;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="box">

<h2>My Tasks</h2>

<?php if($result->num_rows > 0){ ?>

<table>

<tr>
<th>Task ID</th>
<th>Title</th>
<th>Description</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?= $row['taskID'] ?></td>
<td><?= $row['title'] ?></td>
<td><?= $row['description'] ?></td>

<td class="<?= strtolower($row['status']) ?>">
    <?= $row['status'] ?>
</td>

<td><?= $row['date'] ?></td>
</tr>

<?php } ?>

</table>

<?php } else { ?>

<p style="text-align:center;color:red;">
No tasks assigned yet
</p>

<?php } ?>

</div>

</body>
</html>
