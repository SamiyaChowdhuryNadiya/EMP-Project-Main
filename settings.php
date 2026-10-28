<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

/* GET USER DATA */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* UPDATE SETTINGS */
if (isset($_POST['update'])) {

    $name = $_POST['firstName'] . " " . $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $designation = $_POST['designation'];
    $offday = $_POST['offday'];

    $stmt = $conn->prepare("
        UPDATE users 
        SET name=?, email=?, phone=?, position=?, offday=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "sssssi",
        $name,
        $email,
        $phone,
        $designation,
        $offday,
        $user_id
    );

    $stmt->execute();

    header("Location: settings.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Settings</title>

<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial;
    background: #f4f7fb;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    height: 100vh;
    background: #0f172a;
    color: white;
    position: fixed;
    padding: 20px;
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
    margin-left: 240px;
    padding: 30px;
}

.settings {
    background: white;
    padding: 20px;
    border-radius: 10px;
}

.setting {
    margin-bottom: 15px;
}

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
}

.update-btn {
    background: #6366f1;
    color: white;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

<h2>EFFITRACK</h2>

<a href="profile.php">Profile</a>
<a href="settings.php">Settings</a>
<a href="attendance.php">Attendance</a>
<a href="logout.php">Logout</a>

</div>

<!-- MAIN -->
<div class="main">

<h2>Profile Settings</h2>

<div class="settings">

<form method="post">

<div class="setting">
<label>First Name</label>
<input type="text" name="firstName" value="<?php echo explode(' ', $user['name'])[0] ?? ''; ?>">
</div>

<div class="setting">
<label>Last Name</label>
<input type="text" name="lastName" value="<?php echo explode(' ', $user['name'])[1] ?? ''; ?>">
</div>

<div class="setting">
<label>Email</label>
<input type="email" name="email" value="<?php echo $user['email'] ?? ''; ?>">
</div>

<div class="setting">
<label>Phone</label>
<input type="text" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">
</div>

<div class="setting">
<label>Designation</label>
<input type="text" name="designation" value="<?php echo $user['position'] ?? ''; ?>">
</div>

<div class="setting">
<label>Off Day</label>
<input type="text" name="offday" value="<?php echo $user['offday'] ?? ''; ?>">
</div>

<button type="submit" name="update" class="update-btn">Update</button>

</form>

</div>

</div>

</body>
</html>
