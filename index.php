<?php
include "connection.php";

/* INSERT */
if (isset($_POST['submit'])) {

    $building = $_POST['building'];
    $floor = $_POST['floor'];
    $room_number = $_POST['room_number'];

    $stmt = $conn->prepare("INSERT INTO building_info (building, floor, room_number) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $building, $floor, $room_number);
    $stmt->execute();
    $stmt->close();
}

/* DELETE */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM building_info WHERE id=$id");
}

/* FETCH */
$result = $conn->query("SELECT * FROM building_info");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Building Management</title>
</head>
<body>

<h2>Add Building Info</h2>

<form method="POST">
    <input type="text" name="building" placeholder="Building Name" required><br><br>
    <input type="text" name="floor" placeholder="Floor" required><br><br>
    <input type="number" name="room_number" placeholder="Room Number" required><br><br>
    <button type="submit" name="submit">Add</button>
</form>

<hr>

<h2>All Data</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Building</th>
    <th>Floor</th>
    <th>Room</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['building'] ?></td>
    <td><?= $row['floor'] ?></td>
    <td><?= $row['room_number'] ?></td>
    <td>
        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
        <a href="index.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
