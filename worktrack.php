<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.html");
    exit();
}

$instructorID = $_SESSION['user_id'];
$message = "";

/* ADD WORK */
if (isset($_POST['add'])) {

    $taskID = $_POST['taskID'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $status = $_POST['status'];
    $email = $_POST['email'];

    // duration auto calculate (hours)
    $startTime = strtotime($start);
    $endTime = strtotime($end);
    $duration = ($endTime - $startTime) / 3600;

    $stmt = $conn->prepare("
        INSERT INTO instructor_worktrack 
        (entryID, instructorID, taskID, StartTime, EndTime, Duration, status, email)
        VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssiss",
        $instructorID,
        $taskID,
        $start,
        $end,
        $duration,
        $status,
        $email
    );

    if ($stmt->execute()) {
        $message = "Work added successfully!";
    } else {
        $message = "Error adding work!";
    }

    $stmt->close();
}

/* FETCH DATA */
$result = $conn->query("
    SELECT * FROM instructor_worktrack
    WHERE instructorID = '$instructorID'
    ORDER BY StartTime DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Work Track</title>

<style>
body {
    font-family: Arial;
    display: flex;
    background: #f4f7fb;
    margin: 0;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    background: #0f172a;
    color: white;
    height: 100vh;
    padding: 20px;
    position: fixed;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 10px;
    margin: 5px 0;
}

.sidebar a:hover {
    background: #6366f1;
}

/* MAIN */
.main {
    margin-left: 220px;
    padding: 20px;
    width: 100%;
}

/* FORM */
input, select {
    padding: 8px;
    margin: 5px;
    width: 200px;
}

/* TABLE */
table {
    width: 100%;
    background: white;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background: #0f172a;
    color: white;
}

button {
    padding: 10px 15px;
    background: #6366f1;
    color: white;
    border: none;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h3>Instructor</h3>
<a href="instructor_dashboard.php">Dashboard</a>
<a href="worktrack.php">Work Track</a>
<a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>Work Track</h2>

<?php if ($message != "") echo "<p style='color:green'>$message</p>"; ?>

<!-- FORM -->
<form method="post">

<input type="text" name="taskID" placeholder="Task ID" required>

<input type="datetime-local" name="start" required>

<input type="datetime-local" name="end" required>

<select name="status" required>
    <option value="Pending">Pending</option>
    <option value="Completed">Completed</option>
</select>

<input type="email" name="email" placeholder="Email" required>

<button type="submit" name="add">Add Work</button>

</form>

<br>

<!-- TABLE -->
<table>

<tr>
    <th>Entry ID</th>
    <th>Task ID</th>
    <th>Start</th>
    <th>End</th>
    <th>Duration (hrs)</th>
    <th>Status</th>
    <th>Email</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['entryID']; ?></td>
    <td><?php echo $row['taskID']; ?></td>
    <td><?php echo $row['StartTime']; ?></td>
    <td><?php echo $row['EndTime']; ?></td>
    <td><?php echo $row['Duration']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td><?php echo $row['email']; ?></td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
