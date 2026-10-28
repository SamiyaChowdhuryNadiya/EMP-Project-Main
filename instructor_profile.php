<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.html");
    exit();
}

$instructor_id = $_SESSION['user_id'];

/* GET DATA */
$stmt = $conn->prepare("SELECT name, email, designation, profile_pic FROM users WHERE id=?");
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* DEFAULT IMAGE */
$img = !empty($user['profile_pic']) ? $user['profile_pic'] : "default.png";


/* ================= UPLOAD IMAGE ================= */
if (isset($_POST['upload'])) {

    $fileName = $_FILES['pic']['name'];
    $tmpName = $_FILES['pic']['tmp_name'];

    $folder = "uploads/";

    move_uploaded_file($tmpName, $folder . $fileName);

    $stmt = $conn->prepare("UPDATE users SET profile_pic=? WHERE id=?");
    $stmt->bind_param("si", $fileName, $instructor_id);
    $stmt->execute();

    header("Location: instructor_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Instructor Profile</title>

<style>
body{
    font-family:Arial;
    background:#f4f7fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* CARD */
.card{
    background:white;
    padding:30px;
    border-radius:12px;
    width:380px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

/* IMAGE */
img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #6366f1;
    margin-bottom:15px;
}

/* TEXT */
h2{
    margin:10px 0;
}

.designation{
    color:#6366f1;
    font-weight:bold;
    margin-bottom:5px;
}

.email{
    color:#555;
    font-size:14px;
    margin-bottom:15px;
}

/* UPLOAD */
input[type=file]{
    margin-top:10px;
}

button{
    margin-top:10px;
    padding:8px 12px;
    background:#6366f1;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#4f46e5;
}
</style>

</head>

<body>

<div class="card">

<!-- PROFILE IMAGE -->
<img src="uploads/<?= $img ?>">

<!-- NAME -->
<h2><?= $user['name'] ?></h2>

<!-- DESIGNATION -->
<div class="designation">
<?= $user['designation'] ?? 'No Designation Set' ?>
</div>

<!-- EMAIL -->
<div class="email">
<?= $user['email'] ?>
</div>

<!-- UPLOAD FORM -->
<form method="POST" enctype="multipart/form-data">

<input type="file" name="pic" required>

<br>

<button type="submit" name="upload">Update Photo</button>

</form>

</div>

</body>
</html>
