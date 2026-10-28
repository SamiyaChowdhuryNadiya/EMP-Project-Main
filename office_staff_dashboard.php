<?php
session_start();
include "connection.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* SECURITY CHECK */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'office_staff') {
    header("Location: login.php");
    exit();
}

/* STAFF ID */
$id = $_SESSION['user_id'];

/* SAFE COUNT FUNCTION */
function countTable($conn, $sql){

    $result = $conn->query($sql);

    if($result){
        return $result->fetch_assoc()['c'];
    }

    return 0;
}

/* ================= TASKS ================= */

$totalMyTasks = 0;
$totalCompleted = 0;
$totalPending = 0;

/* CHECK TASK TABLE */
$checkTask = $conn->query("SHOW TABLES LIKE 'tasks'");

if($checkTask->num_rows > 0){

    $totalMyTasks = countTable($conn,
        "SELECT COUNT(*) as c FROM tasks WHERE assigned_to='$id'"
    );

    $totalCompleted = countTable($conn,
        "SELECT COUNT(*) as c 
        FROM tasks 
        WHERE assigned_to='$id' 
        AND status='Completed'"
    );

    $totalPending = countTable($conn,
        "SELECT COUNT(*) as c 
        FROM tasks 
        WHERE assigned_to='$id' 
        AND status='Pending'"
    );
}

/* ================= STAFF INFO ================= */

$staffQuery = $conn->query("
    SELECT firstname, lastname, role
    FROM office_staff
    WHERE staffID='$id'
");

$staff = $staffQuery ? $staffQuery->fetch_assoc() : null;

/* ================= SCHEDULE ================= */

$schedule = null;

$checkSchedule = $conn->query("SHOW TABLES LIKE 'staff_workschedule'");

if($checkSchedule->num_rows > 0){

    $scheduleQuery = $conn->query("
        SELECT building, shift, entry_time, exit_time
        FROM staff_workschedule
        WHERE staff_id='$id'
        ORDER BY id DESC LIMIT 1
    ");

    $schedule = $scheduleQuery ? $scheduleQuery->fetch_assoc() : null;
}

/* ================= TASK LIST ================= */

$tasks = null;

if($checkTask->num_rows > 0){

    $tasks = $conn->query("
        SELECT title, status
        FROM tasks
        WHERE assigned_to='$id'
        ORDER BY taskID DESC LIMIT 5
    ");
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Office Staff Dashboard</title>

<style>

body{
    font-family:Arial;
    background:#f4f7fb;
    margin:0;
    display:flex;
}

/* SIDEBAR */

.sidebar{
    width:240px;
    background:#0f172a;
    color:white;
    height:100vh;
    padding:20px;
    position:fixed;
}

.sidebar h2{
    margin-bottom:20px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    margin:10px 0;
    padding:10px;
    border-radius:6px;
}

.sidebar a:hover{
    background:#2563eb;
}

/* PROFILE */

.profile-box{
    text-align:center;
    margin-bottom:25px;
}

.profile-box img{
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid white;
}

.profile-box h3{
    margin-top:10px;
    font-size:18px;
}

/* MAIN */

.main{
    margin-left:260px;
    padding:25px;
    width:100%;
}

/* CARDS */

.card{
    background:white;
    padding:20px;
    margin:10px;
    border-radius:10px;
    display:inline-block;
    width:200px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    text-align:center;
}

.card p{
    font-size:24px;
    font-weight:bold;
    color:#2563eb;
}

/* BOX */

.box{
    background:white;
    padding:20px;
    margin-top:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
}

th{
    background:#0f172a;
    color:white;
}

/* STATUS */

.badge{
    padding:5px 10px;
    border-radius:5px;
    color:white;
}

.completed{
    background:green;
}

.pending{
    background:orange;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h2>Staff Panel</h2>

<!-- PROFILE -->

<div class="profile-box">

<img src="https://cdn-icons-png.flaticon.com/512/149/149071.png">

<h3>
<?= $staff ? $staff['firstname']." ".$staff['lastname'] : "Staff" ?>
</h3>

</div>

<a href="office_staff_dashboard.php">Dashboard</a>

<a href="my_tasks.php">My Tasks</a>

<a href="staff_work_schedule.php">My Schedule</a>

<a href="profile.php">Profile</a>

<a href="logout.php">Logout</a>

</div>

<!-- MAIN -->

<div class="main">

<h2>
Welcome,
<?= $staff ? $staff['firstname']." ".$staff['lastname'] : "Staff" ?>
</h2>

<!-- CARDS -->

<div class="card">
<h3>My Tasks</h3>
<p><?= $totalMyTasks ?></p>
</div>

<div class="card">
<h3>Completed</h3>
<p><?= $totalCompleted ?></p>
</div>

<div class="card">
<h3>Pending</h3>
<p><?= $totalPending ?></p>
</div>

<!-- SCHEDULE -->

<div class="box">

<h3>My Schedule</h3>

<?php if($schedule){ ?>

<p><b>Building:</b> <?= $schedule['building'] ?></p>

<p><b>Shift:</b> <?= $schedule['shift'] ?></p>

<p><b>Entry:</b> <?= $schedule['entry_time'] ?></p>

<p><b>Exit:</b> <?= $schedule['exit_time'] ?></p>

<?php } else { ?>

<p>No schedule assigned</p>

<?php } ?>

</div>

<!-- TASKS -->

<div class="box">

<h3>Recent Tasks</h3>

<table>

<tr>
<th>Task</th>
<th>Status</th>
</tr>

<?php if($tasks && $tasks->num_rows > 0){ ?>

<?php while($t = $tasks->fetch_assoc()){ ?>

<tr>

<td><?= $t['title'] ?></td>

<td>
<span class="badge <?= strtolower($t['status']) ?>">
<?= $t['status'] ?>
</span>
</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="2">No tasks found</td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>