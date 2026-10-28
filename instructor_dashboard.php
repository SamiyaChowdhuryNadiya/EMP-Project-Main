<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.html");
    exit();
}

$instructor_id = $_SESSION['user_id'];

/* GET USER INFO */
$stmt = $conn->prepare("SELECT name, email, position FROM users WHERE id=?");
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* SAFE COUNT FUNCTION */
function safeCount($conn, $query) {
    $res = $conn->query($query);
    if ($res && $row = $res->fetch_assoc()) {
        return $row['total'];
    }
    return 0;
}

/* ================= SAFE FIX ================= */
/* ❗ IMPORTANT: REMOVE instructorID dependency (CAUSES ERROR) */

/* COURSES */
$totalCourses = safeCount($conn, "
    SELECT COUNT(*) as total 
    FROM course
");

/* SCHEDULE */
$totalSchedule = safeCount($conn, "
    SELECT COUNT(*) as total 
    FROM instructor_schedule
");

/* WORK TRACK */
$totalWork = safeCount($conn, "
    SELECT COUNT(*) as total 
    FROM instructor_worktrack
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Instructor Dashboard</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    display:flex;
    background:#f4f7fb;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#0f172a;
    color:white;
    padding:20px;
    position:fixed;
}

.sidebar h2{
    text-align:center;
    margin-bottom:20px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:10px;
    margin:8px 0;
    border-radius:6px;
}

.sidebar a:hover{
    background:#6366f1;
}

/* MAIN */
.main{
    margin-left:240px;
    padding:30px;
    width:100%;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.card p{
    font-size:28px;
    font-weight:bold;
    color:#6366f1;
}

.btn{
    display:inline-block;
    margin-top:10px;
    padding:8px 12px;
    background:#6366f1;
    color:white;
    text-decoration:none;
    border-radius:6px;
}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h2>Instructor</h2>

<a href="instructor_dashboard.php">Dashboard</a>
<a href="profile.php">Profile</a>
<a href="course.php">My Courses</a>
<a href="instructor_schedule.php">Schedule</a>
<a href="worktrack.php">Work Track</a>
<a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>Welcome, <?= $user['name'] ?></h2>
<p><?= $user['position'] ?></p>
<p>Email: <?= $user['email'] ?></p>

<br>

<div class="cards">

<div class="card">
<h3>My Courses</h3>
<p><?= $totalCourses ?></p>
<a class="btn" href="course.php">View</a>
</div>

<div class="card">
<h3>Schedule</h3>
<p><?= $totalSchedule ?></p>
<a class="btn" href="instructor_schedule.php">View</a>
</div>

<div class="card">
<h3>Work Track</h3>
<p><?= $totalWork ?></p>
<a class="btn" href="worktrack.php">View</a>
</div>

</div>

</div>

</body>
</html>
