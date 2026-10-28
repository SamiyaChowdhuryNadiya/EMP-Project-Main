<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ADD SCHEDULE */
if (isset($_POST['add'])) {

    $instructor = $_POST['instructor_id'];
    $course = $_POST['course_id'];
    $section = $_POST['section_id'];
    $day = $_POST['day'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    if ($instructor == "" || $course == "" || $section == "") {
        echo "<script>alert('All fields required!');</script>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO instructor_schedule 
            (instructor_id, course_id, section_id, day, start_time, end_time)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssssss", 
            $instructor, 
            $course, 
            $section, 
            $day, 
            $start, 
            $end
        );

        $stmt->execute();
        header("Location: instructor_schedule.php");
        exit();
    }
}

/* DELETE */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM instructor_schedule WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: instructor_schedule.php");
    exit();
}

/* FETCH DATA */
$result = $conn->query("
    SELECT 
        s.id,
        s.instructor_id,
        s.course_id,
        s.section_id,
        s.day,
        s.start_time,
        s.end_time,
        i.firstname,
        i.lastname,
        c.courseName
    FROM instructor_schedule s
    LEFT JOIN instructor i ON s.instructor_id = i.instructorID
    LEFT JOIN course c ON s.course_id = c.courseCode
    ORDER BY s.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Instructor Schedule</title>

<style>
body{
    font-family: Arial;
    background:#f4f7fb;
    padding:20px;
}
.container{
    max-width:1000px;
    margin:auto;
}
.card{
    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
input,select{
    padding:10px;
    margin:5px;
}
button{
    padding:10px 15px;
    background:#6366f1;
    color:white;
    border:none;
    border-radius:5px;
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
    border-radius:5px;
    text-decoration:none;
}
.back{
    display:inline-block;
    margin-bottom:10px;
    background:#6b7280;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:6px;
}
</style>
</head>

<body>

<div class="container">

<a href="admin_dashboard.php" class="back">← Back</a>

<h2>Instructor Schedule</h2>

<!-- FORM -->
<div class="card">

<form method="POST">

<!-- Instructor Dropdown -->
<select name="instructor_id" required>
<option value="">Select Instructor</option>
<?php
$ins = $conn->query("SELECT instructorID, firstname, lastname FROM instructor");
while($i = $ins->fetch_assoc()){
?>
<option value="<?= $i['instructorID'] ?>">
<?= $i['instructorID'] ?> - <?= $i['firstname'] ?> <?= $i['lastname'] ?>
</option>
<?php } ?>
</select>

<!-- Course Dropdown -->
<select name="course_id" required>
<option value="">Select Course</option>
<?php
$course = $conn->query("SELECT courseCode, courseName FROM course");
while($c = $course->fetch_assoc()){
?>
<option value="<?= $c['courseCode'] ?>">
<?= $c['courseCode'] ?> - <?= $c['courseName'] ?>
</option>
<?php } ?>
</select>

<input type="text" name="section_id" placeholder="Section (A/B/C)" required>

<select name="day" required>
<option value="">Select Day</option>
<option>Sunday</option>
<option>Monday</option>
<option>Tuesday</option>
<option>Wednesday</option>
<option>Thursday</option>
</select>

<input type="time" name="start_time" required>
<input type="time" name="end_time" required>

<button type="submit" name="add">Add Schedule</button>

</form>

</div>

<!-- TABLE -->
<div class="card">

<table>

<tr>
<th>ID</th>
<th>Instructor</th>
<th>Course</th>
<th>Section</th>
<th>Day</th>
<th>Start</th>
<th>End</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?= $row['id'] ?></td>

<td>
<?= $row['instructor_id'] ?> 
(<?= $row['firstname'] ?? 'Unknown' ?> <?= $row['lastname'] ?? '' ?>)
</td>

<td>
<?= $row['course_id'] ?> 
(<?= $row['courseName'] ?? 'Unknown' ?>)
</td>

<td><?= $row['section_id'] ?></td>
<td><?= $row['day'] ?></td>
<td><?= $row['start_time'] ?></td>
<td><?= $row['end_time'] ?></td>

<td>
<a class="delete" href="instructor_schedule.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete?')">
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
