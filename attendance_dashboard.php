<?php
include 'connection.php';

// INSERT ATTENDANCE
if (isset($_POST['add'])) {

    $staffID = $_POST['staffID'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO attendance (staffID, date, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iss", $staffID, $date, $status);

    if (!$stmt->execute()) {
        die("Insert failed: " . $stmt->error);
    }

    $stmt->close();
}

// CHECK IF STAFF TABLE EXISTS
$tableCheck = $conn->query("SHOW TABLES LIKE 'staff'");
$staffTableExists = $tableCheck->num_rows > 0;

// FETCH DATA
if ($staffTableExists) {
    $sql = "SELECT 
                attendance.id,
                attendance.staffID,
                staff.firstname,
                staff.lastname,
                attendance.date,
                attendance.status
            FROM attendance
            JOIN staff ON attendance.staffID = staff.staffID
            ORDER BY attendance.date DESC";
} else {
    $sql = "SELECT * FROM attendance ORDER BY date DESC";
}

$result = $conn->query($sql);

if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Dashboard</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .container {
            width: 90%;
            margin: auto;
        }

        .form-box {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0px 0px 10px #ccc;
        }

        input, select {
            padding: 8px;
            margin: 5px;
        }

        button {
            padding: 8px 15px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px #ccc;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }
    </style>
</head>

<body>

<h2>Attendance Dashboard</h2>

<div class="container">

    <!-- FORM -->
    <div class="form-box">
        <form method="POST" action="">
            <input type="number" name="staffID" placeholder="Staff ID" required>
            <input type="date" name="date" required>

            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Late">Late</option>
            </select>

            <button type="submit" name="add">Add Attendance</button>
        </form>
    </div>

    <!-- TABLE -->
    <table>
        <tr>
            <th>ID</th>
            <th>Staff ID</th>
            <th>Staff Name</th>
            <th>Date</th>
            <th>Status</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {

                // If staff table exists → show name
                if ($staffTableExists) {
                    $fullName = $row['firstname'] . " " . $row['lastname'];
                } else {
                    $fullName = "N/A";
                }

                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['staffID']}</td>
                        <td>{$fullName}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }

        } else {
            echo "<tr><td colspan='5'>No attendance records found</td></tr>";
        }
        ?>

    </table>

</div>

</body>
</html>
