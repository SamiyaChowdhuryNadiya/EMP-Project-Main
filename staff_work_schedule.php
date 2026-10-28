<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'office_staff') {
    header("Location: login.php");
    exit();
}

$staffID = $_SESSION['user_id'];

/* GET SCHEDULE */
$query = $conn->prepare("
    SELECT building, shift, entry_time, exit_time
    FROM staff_workschedule
    WHERE staff_id=?
    ORDER BY id DESC
");

$query->bind_param("s", $staffID);
$query->execute();

$result = $query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Schedule</title>

<style>
body{
    font-family: Arial;
    background:#f4f7fb;
    margin:0;
    padding:20px;
}

.box{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    max-width:900px;
    margin:auto;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

th{
    background:#0f172a;
    color:white;
}

.no-data{
    text-align:center;
    padding:20px;
    color:gray;
}
</style>
</head>

<body>

<div class="box">

<h2>My Work Schedule</h2>

<table>

<tr>
<th>Building</th>
<th>Shift</th>
<th>Entry Time</th>
<th>Exit Time</th>
</tr>

<?php if($result->num_rows > 0){ ?>

    <?php while($row = $result->fetch_assoc()){ ?>

    <tr>
        <td><?= $row['building'] ?></td>
        <td><?= $row['shift'] ?></td>
        <td><?= $row['entry_time'] ?></td>
        <td><?= $row['exit_time'] ?></td>
    </tr>

    <?php } ?>

<?php } else { ?>

<tr>
<td colspan="4" class="no-data">No schedule assigned</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>