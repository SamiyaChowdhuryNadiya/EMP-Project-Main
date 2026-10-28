<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}

/* DELETE USER */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: users.php");
}

/* ADD USER */
if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // AUTO POSITION
    switch ($role) {
        case 'admin': $position = 'Admin'; break;
        case 'instructor': $position = 'Instructor'; break;
        case 'office_staff': $position = 'Office Staff'; break;
        case 'attendance': $position = 'Attendance Officer'; break;
        default: $position = 'Employee';
    }

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, position) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $role, $position);
    $stmt->execute();
}

/* FETCH USERS */
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Users Management</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

/* BASIC */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    display: flex;
    background: #f4f7fb;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
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
    margin: 6px 0;
    border-radius: 6px;
}

.sidebar a:hover {
    background: #6366f1;
}

/* MAIN */
.main {
    margin-left: 240px;
    padding: 20px;
    width: 100%;
}

/* FORM */
.form-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

input, select {
    padding: 10px;
    margin: 5px;
}

/* BUTTON */
button {
    padding: 10px 15px;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 6px;
}

/* TABLE */
table {
    width: 100%;
    background: white;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

th {
    background: #0f172a;
    color: white;
}

/* DELETE BTN */
.delete-btn {
    background: red;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>EFFITRACK</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="attendance.php">Attendance</a>
    <a href="users.php">Users</a>
    <a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>Users Management</h2>

<!-- ADD USER -->
<div class="form-box">
<form method="post">

    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="role" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="instructor">Instructor</option>
        <option value="office_staff">Office Staff</option>
        <option value="attendance">Attendance</option>
    </select>

    <button type="submit" name="add">Add User</button>

</form>
</div>

<!-- USERS TABLE -->
<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Position</th>
    <th>Action</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['role']; ?></td>
    <td><?php echo $row['position']; ?></td>
    <td>
        <a href="users.php?delete=<?php echo $row['id']; ?>" 
           class="delete-btn"
           onclick="return confirm('Delete this user?')">
           Delete
        </a>
    </td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
