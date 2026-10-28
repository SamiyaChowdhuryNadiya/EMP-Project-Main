<?php
session_start();
include "connection.php";

/* SECURITY (ONLY ADMIN) */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* GET ID */
if (!isset($_GET['id'])) {
    echo "No ID found!";
    exit();
}

$id = $_GET['id'];

/* FETCH STAFF DATA */
$stmt = $conn->prepare("SELECT * FROM office_staff WHERE staffID=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

/* IF NOT FOUND */
if (!$row) {
    echo "Staff not found!";
    exit();
}

/* UPDATE */
if (isset($_POST['update'])) {

    $stmt = $conn->prepare("UPDATE office_staff 
    SET firstname=?, lastname=?, phone=?, email=?, salary=?, role=?, status=?, update_date=NOW()
    WHERE staffID=?");

    $stmt->bind_param(
        "ssssssss",
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['salary'],
        $_POST['role'],
        $_POST['status'],
        $id
    );

    $stmt->execute();

    header("Location: officestaff_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Staff</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="container mt-4">

<h2>Edit Staff</h2>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-2">
<label>First Name</label>
<input class="form-control" name="firstname" value="<?= $row['firstname'] ?>" required>
</div>

<div class="col-md-6 mb-2">
<label>Last Name</label>
<input class="form-control" name="lastname" value="<?= $row['lastname'] ?>" required>
</div>

<div class="col-md-6 mb-2">
<label>Phone</label>
<input class="form-control" name="phone" value="<?= $row['phone'] ?>">
</div>

<div class="col-md-6 mb-2">
<label>Email</label>
<input class="form-control" name="email" value="<?= $row['email'] ?>">
</div>

<div class="col-md-6 mb-2">
<label>Salary</label>
<input class="form-control" name="salary" value="<?= $row['salary'] ?>">
</div>

<div class="col-md-6 mb-2">
<label>Role</label>
<input class="form-control" name="role" value="<?= $row['role'] ?>">
</div>

<div class="col-md-6 mb-2">
<label>Status</label>
<select class="form-control" name="status">
    <option value="Active" <?= $row['status']=="Active" ? "selected" : "" ?>>Active</option>
    <option value="Inactive" <?= $row['status']=="Inactive" ? "selected" : "" ?>>Inactive</option>
</select>
</div>

<div class="col-md-12 mt-3">
<button class="btn btn-success" name="update">Update Staff</button>
<a class="btn btn-secondary" href="officestaff_list.php">Back</a>
</div>

</div>

</form>

</body>
</html>
