<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="admin_instructor.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header>
        <h2 class="logo">Logo</h2>
        <nav class="navigation">
            <a href="instructor_list.html">Instructors</a>
            <a href="officestaff_list.html">Office Staff</a>
            <a href="attandance.html">Attendance</a>
            <a href="logout.php" class="cta-button">Logout</a>
        </nav>
    </header>

<div class="container mt-3">
  <h2>Hover Rows</h2>
  <p>The .table-hover class enables a hover state (grey background on mouse over) on table rows:</p>            
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Details</th>
      </tr>
    </thead>
    <tbody>
    <?php
include 'connection.php';

$sql = "SELECT name, email, role FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["role"] . "</td>";
        echo "<td><a href='profile.html' class='btn btn-primary' >View Details</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No users found</td></tr>";
}

$conn->close();
?>
    </tbody>
  </table>
</div>

</body>
</html>
