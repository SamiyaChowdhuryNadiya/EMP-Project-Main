<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* ADD STAFF */
if (isset($_POST['submit'])) {

    $status = "Active";

    $stmt = $conn->prepare("INSERT INTO office_staff 
    (staffID, firstname, lastname, phone, salary, role, status)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssss",
        $_POST['staffID'],
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['phone'],
        $_POST['salary'],
        $_POST['role'],
        $status
    );

    $stmt->execute();
    header("Location: officestaff_list.php");
    exit();
}

/* SEARCH */
$search = "";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = $_GET['search'];

    $result = $conn->query("
        SELECT * FROM office_staff 
        WHERE staffID LIKE '%$search%'
        OR firstname LIKE '%$search%'
        OR lastname LIKE '%$search%'
        OR role LIKE '%$search%'
    ");

} else {
    $result = $conn->query("SELECT * FROM office_staff");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Office Staff Management</title>

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
<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="m-0">Office Staff Management</h2>

    <a href="admin_dashboard.php" class="btn btn-secondary">
        ← Back
    </a>

</div>

<!-- SEARCH BOX -->
<form method="GET" class="mb-3">

<div class="input-group">

<input type="text" name="search" class="form-control"
placeholder="Search by ID, Name or Role..."
value="<?= $search ?>">

<button class="btn btn-primary" type="submit">Search</button>

<a href="officestaff_list.php" class="btn btn-secondary">Reset</a>

</div>

</form>

<!-- ADD STAFF FORM -->
<div class="card p-3 mb-4">

<form method="POST">

<div class="row g-3">

<div class="col-md-3">
<input class="form-control" name="staffID" placeholder="Staff ID" required>
</div>

<div class="col-md-3">
<input class="form-control" name="firstname" placeholder="First Name" required>
</div>

<div class="col-md-3">
<input class="form-control" name="lastname" placeholder="Last Name" required>
</div>

<div class="col-md-3">
<input class="form-control" name="phone" placeholder="Phone">
</div>

<div class="col-md-3">
<input class="form-control" name="salary" placeholder="Salary">
</div>

<div class="col-md-3">
<input class="form-control" name="role" placeholder="Role">
</div>

<div class="col-md-3 d-grid">
<button class="btn btn-success" name="submit">Add Staff</button>
</div>

</div>

</form>

</div>

<!-- TABLE -->
<div class="card p-3">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Salary</th>
<th>Role</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php if ($result && $result->num_rows > 0) { ?>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?= $row['staffID'] ?></td>
<td><?= $row['firstname']." ".$row['lastname'] ?></td>
<td><?= $row['phone'] ?></td>
<td><?= $row['salary'] ?></td>
<td><?= $row['role'] ?></td>

<td>
<span class="badge bg-success">
<?= $row['status'] ?>
</span>
</td>

<td>
<a class="btn btn-warning btn-sm" href="edit_staff.php?id=<?= $row['staffID'] ?>">Edit</a>

<a class="btn btn-danger btn-sm"
href="delete_staff.php?id=<?= $row['staffID'] ?>"
onclick="return confirm('Delete this staff?')">
Delete
</a>
</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="7" class="text-center">No staff found</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>
