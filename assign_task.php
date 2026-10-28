<?php
session_start();
include "connection.php";

/* ONLY ADMIN */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ADD TASK */
if(isset($_POST['add'])){

    $stmt = $conn->prepare("INSERT INTO tasks (taskID, title, description, assigned_to, status)
                            VALUES (?, ?, ?, ?, 'Pending')");

    $stmt->bind_param("ssss",
        $_POST['taskID'],
        $_POST['title'],
        $_POST['description'],
        $_POST['assigned_to']
    );

    $stmt->execute();
    header("Location: assign_task.php");
    exit();
}

/* GET STAFF LIST */
$staff = $conn->query("SELECT staffID, firstname, lastname FROM office_staff");

/* GET TASKS */
$tasks = $conn->query("SELECT * FROM tasks ORDER BY taskID DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Assign Tasks</title>
<style>
body{font-family:Arial;background:#f4f7fb;padding:20px;}
.card{background:white;padding:20px;border-radius:10px;margin-bottom:20px;}
table{width:100%;border-collapse:collapse;background:white;}
th,td{padding:10px;border:1px solid #ddd;text-align:center;}
th{background:#0f172a;color:white;}
</style>
</head>

<body>

<h2>Assign Task (Admin)</h2>

<div class="card">

<form method="POST">

<input type="text" name="taskID" placeholder="Task ID (TSK001)" required>
<br><br>

<input type="text" name="title" placeholder="Task Title" required>
<br><br>

<textarea name="description" placeholder="Description"></textarea>
<br><br>

<select name="assigned_to" required>
<option value="">Select Staff</option>

<?php while($s = $staff->fetch_assoc()){ ?>
<option value="<?= $s['staffID'] ?>">
<?= $s['firstname']." ".$s['lastname'] ?>
</option>
<?php } ?>

</select>

<br><br>

<button name="add">Assign Task</button>

</form>

</div>

<!-- TASK TABLE -->
<table>

<tr>
<th>ID</th>
<th>Title</th>
<th>Assigned To</th>
<th>Status</th>
</tr>

<?php while($t = $tasks->fetch_assoc()){ ?>

<tr>
<td><?= $t['taskID'] ?></td>
<td><?= $t['title'] ?></td>
<td><?= $t['assigned_to'] ?></td>
<td><?= $t['status'] ?></td>
</tr>

<?php } ?>

</table>

</body>
</html>
