<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ADD DEPARTMENT */
if (isset($_POST['add'])) {

    $stmt = $conn->prepare("INSERT INTO dept_info (dept_name, designation, dept_head, building) VALUES (?, ?, ?, ?)");
    $stmt->bind_param(
        "ssss",
        $_POST['dept_name'],
        $_POST['designation'],
        $_POST['dept_head'],
        $_POST['building']
    );

    $stmt->execute();
    header("Location: dept_info.php");
    exit();
}

/* DELETE */
if (isset($_GET['delete'])) {
    $dept = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM dept_info WHERE dept_name=?");
    $stmt->bind_param("s", $dept);
    $stmt->execute();

    header("Location: dept_info.php");
    exit();
}

/* EDIT LOAD */
$editData = null;
if (isset($_GET['edit'])) {
    $dept = $_GET['edit'];

    $stmt = $conn->prepare("SELECT * FROM dept_info WHERE dept_name=?");
    $stmt->bind_param("s", $dept);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
}

/* UPDATE */
if (isset($_POST['update'])) {

    $stmt = $conn->prepare("UPDATE dept_info SET designation=?, dept_head=?, building=? WHERE dept_name=?");
    $stmt->bind_param(
        "ssss",
        $_POST['designation'],
        $_POST['dept_head'],
        $_POST['building'],
        $_POST['dept_name']
    );

    $stmt->execute();
    header("Location: dept_info.php");
    exit();
}

/* FETCH ALL */
$result = $conn->query("SELECT * FROM dept_info ORDER BY dept_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Department Info</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f7fb;
    padding:20px;
}

.card{
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}
</style>
</head>

<body>

<div class="container">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Department Info</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary">← Back</a>
</div>

<!-- FORM -->
<div class="card p-3 mb-3">

<form method="POST">

<div class="row g-2">

<input type="hidden" name="dept_name" value="<?= $editData['dept_name'] ?? '' ?>">

<div class="col-md-3">
<input class="form-control" name="dept_name"
placeholder="Department Name"
value="<?= $editData['dept_name'] ?? '' ?>"
<?= $editData ? 'readonly' : '' ?>
required>
</div>

<div class="col-md-3">
<input class="form-control" name="designation"
placeholder="Designation"
value="<?= $editData['designation'] ?? '' ?>" required>
</div>

<div class="col-md-3">
<input class="form-control" name="dept_head"
placeholder="Dept Head"
value="<?= $editData['dept_head'] ?? '' ?>" required>
</div>

<div class="col-md-3">
<input class="form-control" name="building"
placeholder="Building"
value="<?= $editData['building'] ?? '' ?>" required>
</div>

<div class="col-md-3">
<?php if ($editData) { ?>
    <button class="btn btn-warning w-100" name="update">Update</button>
<?php } else { ?>
    <button class="btn btn-success w-100" name="add">Add Department</button>
<?php } ?>
</div>

</div>

</form>

</div>

<!-- TABLE -->
<div class="card p-3">

<table class="table table-bordered table-hover">

<thead class="table-dark">
<tr>
<th>Department</th>
<th>Designation</th>
<th>Dept Head</th>
<th>Building</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
<td><?= $row['dept_name'] ?></td>
<td><?= $row['designation'] ?></td>
<td><?= $row['dept_head'] ?></td>
<td><?= $row['building'] ?></td>

<td>
<a class="btn btn-warning btn-sm"
href="dept_info.php?edit=<?= $row['dept_name'] ?>">
Edit
</a>

<a class="btn btn-danger btn-sm"
href="dept_info.php?delete=<?= $row['dept_name'] ?>"
onclick="return confirm('Delete this department?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>
