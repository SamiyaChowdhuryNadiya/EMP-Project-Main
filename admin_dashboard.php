<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

/* SAFE COUNT FUNCTION */
function getCount($conn, $table) {
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    if (!$check || $check->num_rows == 0) return 0;

    $result = $conn->query("SELECT COUNT(*) as total FROM $table");
    return $result ? $result->fetch_assoc()['total'] : 0;
}

/* COUNTS */
$totalInstructors = getCount($conn, "instructor");
$totalStaff       = getCount($conn, "office_staff");
$totalAttendance  = getCount($conn, "attendance");
$totalMessages    = getCount($conn, "contact");

$totalDept        = getCount($conn, "dept_info");
$totalBuilding    = getCount($conn, "building_info");
$totalCourse      = getCount($conn, "course");
$totalInstSched   = getCount($conn, "instructor_schedule");
$totalStaffSched  = getCount($conn, "staff_work_schedule");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial;
}

body{
    display:flex;
    background:#f4f7fb;
}

/* SIDEBAR */
.sidebar{
    width:250px;
    height:100vh;
    background:#0f172a;
    color:white;
    padding:20px;
    position:fixed;
    overflow-y:auto;
}

.sidebar h2{
    margin-bottom:20px;
    color:#38bdf8;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:10px;
    margin:5px 0;
    border-radius:6px;
}

.sidebar a:hover{
    background:#6366f1;
}

/* MAIN */
.main{
    margin-left:250px;
    padding:25px;
    width:100%;
}

.header{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

.logout{
    background:red;
    color:white;
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
    gap:15px;
}

.card{
    background:white;
    padding:15px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 5px 10px rgba(0,0,0,0.05);
}

.card p{
    font-size:24px;
    font-weight:bold;
    color:#6366f1;
}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<h2>EFFITRACK ADMIN</h2>

<a href="admin_dashboard.php">🏠 Dashboard</a>

<a href="dept_info.php">🏢 Department Info</a>
<a href="building_info.php">🏬 Building Info</a>
<a href="course.php">📚 Course</a>

<a href="instructor.php">👨‍🏫 Instructor Management</a>
<a href="instructor_list.php">📋 Instructor List</a>

<a href="officestaff_list.php">👷 Staff Management</a>

<a href="attendance.php">🕒 Attendance</a>
<a href="instructor_schedule.php">📅 Instructor Schedule</a>
<a href="staff_work_schedule.php">📋 Staff Schedule</a>

<a href="admin_messages.php">✉ Messages</a>
<a href="logout.php">🚪 Logout</a>

</div>

<!-- MAIN -->
<div class="main">

<div class="header">
    <h2>Admin Dashboard</h2>
    <a class="logout" href="logout.php">Logout</a>
</div>

<!-- ONLY CARDS (NO TABLE ANYMORE) -->
<div class="cards">

    <div class="card">
        <h3>Departments</h3>
        <p><?= $totalDept ?></p>
    </div>

    <div class="card">
        <h3>Buildings</h3>
        <p><?= $totalBuilding ?></p>
    </div>

    <div class="card">
        <h3>Courses</h3>
        <p><?= $totalCourse ?></p>
    </div>

    <div class="card">
        <h3>Instructors</h3>
        <p><?= $totalInstructors ?></p>
    </div>

    <div class="card">
        <h3>Staff</h3>
        <p><?= $totalStaff ?></p>
    </div>

    <div class="card">
        <h3>Attendance</h3>
        <p><?= $totalAttendance ?></p>
    </div>

    <div class="card">
        <h3>Instructor Schedule</h3>
        <p><?= $totalInstSched ?></p>
    </div>

    <div class="card">
        <h3>Staff Schedule</h3>
        <p><?= $totalStaffSched ?></p>
    </div>

    <div class="card">
        <h3>Messages</h3>
        <p><?= $totalMessages ?></p>
    </div>

</div>

</div>

</body>
</html>