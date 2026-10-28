<?php
include "connection.php";

$staff_id = $_GET['staff_id'] ?? '';
$result = null;

/* FETCH STAFF LIST (for dropdown) */
$staffList = $conn->query("SELECT staffID, firstname, lastname FROM office_staff");

if ($staff_id != "") {

    $stmt = $conn->prepare("
        SELECT * FROM staff_workschedule 
        WHERE staff_id=?
    ");

    $stmt->bind_param("s", $staff_id);
    $stmt->execute();

    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Staff Schedule</title>

<style>
body{
    font-family: Arial;
    background:#f4f7fb;
    padding:20px;
}

.box{
    background:white;
    padding:20px;
    border-radius:10px;
    max-width:700px;
    margin:auto;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
}

input, select, button{
    padding:10px;
    width:100%;
    margin:8px 0;
}

button{
    background:#6366f1;
    color:white;
    border:none;
    cursor:pointer;
    border-radius:5px;
}

button:hover{
    background:#4f46e5;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th,td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#0f172a;
    color:white;
}

.back{
    display:inline-block;
    margin-top:10px;
    text-decoration:none;
    color:#6366f1;
}
</style>

</head>

<body>

<div class="box">

<h2>Check Staff Schedule</h2>

<!-- 🔥 SEARCH FORM (NO MANUAL INPUT ERROR) -->
<form method="GET">

<select name="staff_id" required>
    <option value="">-- Select Staff --</option>

    <?php while($s = $staffList->fetch_assoc()){ ?>
        <option value="<?= $s['staffID'] ?>"
        <?= ($staff_id == $s['staffID']) ? 'selected' : '' ?>>

            <?= $s['staffID'] ?> - <?= $s['firstname'] ?> <?= $s['lastname'] ?>

        </option>
    <?php } ?>

</select>

<button type="submit">View Schedule</button>

</form>

<!-- 🔥 RESULTS -->
<?php if ($staff_id != "") { ?>

    <?php if ($result && $result->num_rows > 0) { ?>

        <table>

        <tr>
        <th>Building</th>
        <th>Shift</th>
        <th>Entry Time</th>
        <th>Exit Time</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>

        <tr>
        <td><?= $row['building'] ?></td>
        <td><?= $row['shift'] ?></td>
        <td><?= $row['entry_time'] ?></td>
        <td><?= $row['exit_time'] ?></td>
        </tr>

        <?php } ?>

        </table>

    <?php } else { ?>

        <p style="color:red;text-align:center;">
            No schedule found for this staff
        </p>

    <?php } ?>

<?php } ?>

<!-- BACK -->
<a class="back" href="office_staff_dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>
