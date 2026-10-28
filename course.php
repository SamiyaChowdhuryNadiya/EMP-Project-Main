<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ADD COURSE (SAFE) */
if (isset($_POST['add'])) {

    $insID = $_POST['instructorID'];

    // check instructor exists
    $check = $conn->prepare("SELECT instructorID FROM instructor WHERE instructorID=?");
    $check->bind_param("s", $insID);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows == 0) {
        echo "<script>alert('Invalid Instructor ID!');</script>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO course (courseCode, courseName, time, instructorID)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssss",
            $_POST['courseCode'],
            $_POST['courseName'],
            $_POST['time'],
            $insID
        );

        $stmt->execute();
        header("Location: course.php");
        exit();
    }
}

/* DELETE COURSE */
if (isset($_GET['delete'])) {

    $code = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM course WHERE courseCode=?");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    header("Location: course.php");
    exit();
}

/* FETCH COURSE + INSTRUCTOR JOIN */
$result = $conn->query("
    SELECT c.*, i.firstname, i.lastname
    FROM course c
    LEFT JOIN instructor i ON c.instructorID = i.instructorID
    ORDER BY c.courseCode DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Course Management</title>

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
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

input, select{
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

<!-- BACK -->
<a href="admin_dashboard.php" class="back-btn">← Back</a>

<h2>📚 Course Management</h2>

<!-- ADD FORM -->
<div class="card">

<form method="POST">

<input type="text" name="courseCode" placeholder="Course Code" required>
<input type="text" name="courseName" placeholder="Course Name" required>
<input type="text" name="time" placeholder="Time (e.g 9-11 AM)" required>

<!-- DROPDOWN (FIXED) -->
<select name="instructorID" required>
    <option value="">Select Instructor</option>

    <?php
    $ins = $conn->query("SELECT instructorID, firstname, lastname FROM instructor");
    while($i = $ins->fetch_assoc()){
        echo "<option value='{$i['instructorID']}'>
            {$i['instructorID']} - {$i['firstname']} {$i['lastname']}
        </option>";
    }
    ?>
</select>

<button type="submit" name="add">Add Course</button>

</form>

</div>

<!-- TABLE -->
<div class="card">

<table>

<tr>
<th>SL</th>
<th>Code</th>
<th>Name</th>
<th>Time</th>
<th>Instructor</th>
<th>Action</th>
</tr>

<?php 
$sl = 1;
while($row = $result->fetch_assoc()){ 
?>

<tr>
<td><?= $sl++ ?></td>
<td><?= $row['courseCode'] ?></td>
<td><?= $row['courseName'] ?></td>
<td><?= $row['time'] ?></td>
<td>
<?= $row['instructorID'] ?> <br>
<small>
<?= $row['firstname']." ".$row['lastname'] ?>
</small>
</td>

<td>
<a class="delete"
href="course.php?delete=<?= $row['courseCode'] ?>"
onclick="return confirm('Delete course?')">
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
