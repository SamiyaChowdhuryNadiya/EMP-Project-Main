<?php
session_start();
include "connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM instructor WHERE instructorID=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$inst = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Instructor Details</title>

<style>
body{
    font-family:Arial;
    background:#f4f7fb;
}

.box{
    width:50%;
    margin:auto;
    margin-top:40px;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    margin-bottom:15px;
    text-align:center;
}

/* IMAGE */
.profile{
    text-align:center;
    margin-bottom:20px;
}

.profile img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #6366f1;
}

p{
    padding:8px 0;
    font-size:15px;
}
</style>

</head>

<body>

<div class="box">

<h2>👨‍🏫 Instructor Details</h2>

<!-- IMAGE -->
<div class="profile">

<?php if(!empty($inst['profile_image'])) { ?>

<img src="uploads/<?= $inst['profile_image'] ?>">

<?php } else { ?>

<img src="https://cdn-icons-png.flaticon.com/512/149/149071.png">

<?php } ?>

</div>

<p><b>ID:</b> <?= $inst['instructorID'] ?></p>
<p><b>Name:</b> <?= $inst['firstname']." ".$inst['lastname'] ?></p>
<p><b>Email:</b> <?= $inst['email'] ?></p>
<p><b>Phone:</b> <?= $inst['phone'] ?></p>
<p><b>Designation:</b> <?= $inst['designation'] ?></p>
<p><b>Department:</b> <?= $inst['dept_name'] ?></p>
<p><b>Room:</b> <?= $inst['room_number'] ?></p>
<p><b>Ranking:</b> <?= $inst['ranking'] ?></p>

</div>

</body>
</html>