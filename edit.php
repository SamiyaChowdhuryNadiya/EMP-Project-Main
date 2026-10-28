<?php
include "connection.php";

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM instructor WHERE instructorID=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    die("Instructor not found");
}

/* UPDATE */
if (isset($_POST['update'])) {

    $firstname = $_POST['firstname'];
    $lastname  = $_POST['lastname'];
    $phone     = $_POST['phone'];
    $email     = $_POST['email'];
    $dept_name = $_POST['dept_name'];
    $room      = $_POST['room_number'];
    $designation = $_POST['designation'];
    $ranking   = $_POST['ranking'];
    $off_day   = $_POST['off_day'];

    $image = $row['profile_image'];

    if (!empty($_FILES['profile_image']['name'])) {
        $image = time() . "_" . $_FILES['profile_image']['name'];
        move_uploaded_file($_FILES['profile_image']['tmp_name'], "uploads/" . $image);
    }

    $stmt = $conn->prepare("
        UPDATE instructor 
        SET firstname=?, lastname=?, phone=?, email=?, dept_name=?, room_number=?, designation=?, ranking=?, off_day=?, profile_image=? 
        WHERE instructorID=?
    ");

    $stmt->bind_param(
        "sssssssssss",
        $firstname,
        $lastname,
        $phone,
        $email,
        $dept_name,
        $room,
        $designation,
        $ranking,
        $off_day,
        $image,
        $id
    );

    $stmt->execute();

    header("Location: instructor.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Instructor</title>

<style>
body{
    font-family: Arial;
    background: #f4f7fb;
    margin:0;
}

/* CARD */
.container{
    width: 500px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* TITLE */
h2{
    text-align:center;
    margin-bottom:20px;
    color:#1e293b;
}

/* IMAGE */
.profile-box{
    text-align:center;
    margin-bottom:15px;
}

.profile-box img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #6366f1;
}

/* INPUT */
input{
    width:100%;
    padding:10px;
    margin:7px 0;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
}

input:focus{
    border-color:#6366f1;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    margin-top:10px;
    background:#6366f1;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#4f46e5;
}

/* FILE */
.file{
    margin:10px 0;
}

</style>
</head>

<body>

<div class="container">

<h2>👨‍🏫 Edit Instructor</h2>

<!-- CURRENT IMAGE -->
<div class="profile-box">
    <?php if(!empty($row['profile_image'])) { ?>
        <img src="uploads/<?= $row['profile_image'] ?>">
    <?php } else { ?>
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png">
    <?php } ?>
</div>

<form method="POST" enctype="multipart/form-data">

<input type="file" name="profile_image" class="file">

<input type="text" name="firstname" value="<?= $row['firstname'] ?>" placeholder="First Name">
<input type="text" name="lastname" value="<?= $row['lastname'] ?>" placeholder="Last Name">

<input type="text" name="phone" value="<?= $row['phone'] ?>" placeholder="Phone">
<input type="email" name="email" value="<?= $row['email'] ?>" placeholder="Email">

<input type="text" name="dept_name" value="<?= $row['dept_name'] ?>" placeholder="Department">
<input type="text" name="room_number" value="<?= $row['room_number'] ?>" placeholder="Room Number">

<input type="text" name="designation" value="<?= $row['designation'] ?>" placeholder="Designation">
<input type="text" name="ranking" value="<?= $row['ranking'] ?>" placeholder="Ranking">
<input type="text" name="off_day" value="<?= $row['off_day'] ?>" placeholder="Off Day">

<button type="submit" name="update">Update Instructor</button>

</form>

</div>

</body>
</html>