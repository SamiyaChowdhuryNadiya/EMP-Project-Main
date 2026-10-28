<?php
include "connection.php";

/* INSERT INSTRUCTOR */
if (isset($_POST['submit'])) {

    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    /* IMAGE UPLOAD */
    $imageName = "";
    if (!empty($_FILES['profile_image']['name'])) {

        $imageName = time() . "_" . $_FILES['profile_image']['name'];
        move_uploaded_file($_FILES['profile_image']['tmp_name'], "uploads/" . $imageName);
    }

    $stmt = $conn->prepare("INSERT INTO instructor 
    (instructorID, firstname, lastname, pass, phone, email, date_of_birth, salary, joining_date, dept_name, room_number, designation, ranking, off_day, profile_image)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssssssssss",
        $_POST['instructorID'],
        $_POST['firstname'],
        $_POST['lastname'],
        $password,
        $_POST['phone'],
        $_POST['email'],
        $_POST['date_of_birth'],
        $_POST['salary'],
        $_POST['joining_date'],
        $_POST['dept_name'],
        $_POST['room_number'],
        $_POST['designation'],
        $_POST['ranking'],
        $_POST['off_day'],
        $imageName
    );

    $stmt->execute();
    header("Location: instructor.php");
    exit();
}

/* TOTAL */
$total = $conn->query("SELECT COUNT(*) as c FROM instructor")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Instructor Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background: #f5f7fb; }

/* SIDEBAR */
.sidebar {
    height: 100vh;
    width: 250px;
    position: fixed;
    background: #1e293b;
    color: white;
    padding: 20px;
}
.sidebar a {
    display: block;
    color: white;
    padding: 10px;
    text-decoration: none;
    margin-bottom: 10px;
    border-radius: 5px;
}
.sidebar a:hover { background: #38bdf8; }

.main { margin-left: 260px; padding: 20px; }

.card-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
}

/* IMAGE */
img.profile {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
</head>

<body>

<div class="sidebar">
    <h3>University LMS</h3>
    <a href="#">Dashboard</a>
    <a href="#">Instructors</a>
    <a href="#">Students</a>
    <a href="#">Departments</a>
</div>

<div class="main">

<h2>Instructor Dashboard</h2>

<div class="card-box mb-3">
    <h5>Total Instructors: <?= $total ?></h5>
</div>

<!-- ADD BUTTON -->
<button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addModal">
    + Add Instructor
</button>

<div style="clear:both;"></div>

<!-- TABLE -->
<table class="table table-bordered mt-3">
<tr class="table-dark">
    <th>Image</th>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Dept</th>
    <th>Phone</th>
    <th>Salary</th>
    <th>Joining</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM instructor");
while($row = $result->fetch_assoc()) {
?>
<tr>

    <!-- IMAGE -->
    <td>
        <?php if(!empty($row['profile_image'])) { ?>
            <img class="profile" src="uploads/<?= $row['profile_image'] ?>">
        <?php } else { ?>
            <img class="profile" src="https://cdn-icons-png.flaticon.com/512/149/149071.png">
        <?php } ?>
    </td>

    <td><?= $row['instructorID'] ?></td>
    <td><?= $row['firstname']." ".$row['lastname'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['dept_name'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td><?= $row['salary'] ?></td>
    <td><?= $row['joining_date'] ?></td>

    <td>
        <a class="btn btn-danger btn-sm" href="delete.php?id=<?= $row['instructorID'] ?>">Delete</a>
        <a class="btn btn-warning btn-sm" href="edit.php?id=<?= $row['instructorID'] ?>">Edit</a>
    </td>
</tr>
<?php } ?>
</table>

</div>

<!-- MODAL -->
<div class="modal fade" id="addModal">
<div class="modal-dialog modal-lg">
<div class="modal-content p-3">

<h4>Add Instructor</h4>

<form method="POST" enctype="multipart/form-data">

<div class="row">

<input class="form-control mb-2" name="instructorID" placeholder="Instructor ID" required>
<input class="form-control mb-2" name="firstname" placeholder="First Name" required>
<input class="form-control mb-2" name="lastname" placeholder="Last Name" required>
<input class="form-control mb-2" type="password" name="pass" placeholder="Password" required>

<input class="form-control mb-2" type="file" name="profile_image">

<input class="form-control mb-2" name="phone" placeholder="Phone">
<input class="form-control mb-2" name="email" placeholder="Email">
<input class="form-control mb-2" type="date" name="date_of_birth">
<input class="form-control mb-2" name="salary" placeholder="Salary">
<input class="form-control mb-2" type="date" name="joining_date">

<input class="form-control mb-2" name="dept_name" placeholder="Department">
<input class="form-control mb-2" name="room_number" placeholder="Room">
<input class="form-control mb-2" name="designation" placeholder="Designation">
<input class="form-control mb-2" name="ranking" placeholder="Ranking">
<input class="form-control mb-2" name="off_day" placeholder="Off Day">

<button class="btn btn-success w-100" name="submit">Save</button>

</div>
</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>