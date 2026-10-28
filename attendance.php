<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";
$messageColor = "green";

/* ADD ATTENDANCE */
if (isset($_POST['add'])) {

    $staff_id = $_POST['staff_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    // validation
    if ($staff_id == "" || $date == "") {
        $message = "❌ Invalid input!";
        $messageColor = "red";
    } else {

        // duplicate check
        $check = $conn->prepare("SELECT id FROM attendance WHERE staff_id=? AND date=?");
        $check->bind_param("ss", $staff_id, $date);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $message = "⚠ Already marked!";
            $messageColor = "red";
        } else {

            $stmt = $conn->prepare("INSERT INTO attendance (staff_id, date, status) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $staff_id, $date, $status);
            $stmt->execute();

            $message = "✔ Attendance saved!";
        }
    }
}

/* FETCH DATA (FIXED JOIN) */
$result = $conn->query("
    SELECT 
        a.staff_id,
        a.date,
        a.status,
        s.firstname,
        s.lastname,
        s.role
    FROM attendance a
    LEFT JOIN office_staff s 
    ON a.staff_id = s.staffID
    ORDER BY a.date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance System</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:Poppins;
    margin:0;
    background:#f4f7fb;
}

.container{
    margin-left:260px;
    padding:30px;
}

.sidebar{
    width:240px;
    height:100vh;
    background:#0f172a;
    color:white;
    position:fixed;
    padding:20px;
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
    background:#6366f1;
}

.form-box{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

input, select{
    padding:10px;
    margin:5px;
    width:200px;
}

button{
    padding:10px 15px;
    background:#6366f1;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

table{
    width:100%;
    background:white;
    border-collapse:collapse;
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

.msg{
    font-weight:bold;
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="sidebar">
    <h3>ADMIN PANEL</h3>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="attendance.php">Attendance</a>
    <a href="officestaff_list.php">Staff</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

<h2>Attendance System</h2>

<?php if ($message != "") { ?>
<p class="msg" style="color:<?= $messageColor ?>">
    <?= $message ?>
</p>
<?php } ?>

<!-- FORM -->
<div class="form-box">

<form method="post">

<!-- FIXED: dropdown instead of typing -->
<select name="staff_id" required>
    <option value="">Select Staff</option>
    <?php
    $staff = $conn->query("SELECT staffID, firstname FROM office_staff");
    while($s = $staff->fetch_assoc()){
    ?>
    <option value="<?= $s['staffID'] ?>">
        <?= $s['staffID']." - ".$s['firstname'] ?>
    </option>
    <?php } ?>
</select>

<input type="date" name="date" required>

<select name="status" required>
    <option value="Present">Present</option>
    <option value="Absent">Absent</option>
</select>

<button type="submit" name="add">Mark Attendance</button>

</form>

</div>

<!-- TABLE -->
<table>

<tr>
    <th>Staff ID</th>
    <th>Name</th>
    <th>Role</th>
    <th>Date</th>
    <th>Status</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
    <td><?= $row['staff_id'] ?></td>

    <td>
        <?= $row['firstname'] 
            ? $row['firstname']." ".$row['lastname'] 
            : "Unknown" ?>
    </td>

    <td>
        <?= $row['role'] ? $row['role'] : "N/A" ?>
    </td>

    <td><?= $row['date'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>
