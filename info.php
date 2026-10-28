<?php
session_start();
include "connection.php";

/* SECURITY */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

/* FETCH USER */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* UPDATE PROFILE */
if (isset($_POST['update_profile'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $position = $_POST['position'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, address=?, position=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $position, $user_id);

    $stmt->execute();

    header("Location: info.php");
}

/* CHANGE PASSWORD */
if (isset($_POST['change_password'])) {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    if (password_verify($old, $user['password'])) {

        $newPass = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $newPass, $user_id);
        $stmt->execute();

        echo "<script>alert('Password Updated');</script>";
    } else {
        echo "<script>alert('Wrong Old Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Profile</title>

<style>
body { font-family: Arial; background:#f4f7fb; }
.container { display:flex; width:90%; margin:auto; margin-top:30px; }
.leftbox { width:20%; }
.tab { padding:12px; background:#0f172a; color:white; margin:5px; cursor:pointer; }
.tab.active { background:#6366f1; }
.rightbox { width:80%; background:white; padding:20px; }
.tabshow { display:none; }
.tabshow.active { display:block; }
.input { width:100%; padding:10px; margin:5px 0; }
.btn { padding:10px; background:#6366f1; color:white; border:none; }
</style>
</head>

<body>

<div class="container">

<!-- LEFT -->
<div class="leftbox">
    <div class="tab active" onclick="openTab(0)">Profile</div>
    <div class="tab" onclick="openTab(1)">Password</div>
</div>

<!-- RIGHT -->
<div class="rightbox">

<!-- PROFILE -->
<div class="tabshow active">

<h2>Profile Info</h2>

<form method="post">

<input type="text" name="name" class="input" value="<?php echo $user['name']; ?>" required>

<input type="email" name="email" class="input" value="<?php echo $user['email']; ?>" required>

<input type="text" name="phone" class="input" value="<?php echo $user['phone']; ?>">

<input type="text" name="address" class="input" value="<?php echo $user['address']; ?>">

<input type="text" name="position" class="input" value="<?php echo $user['position']; ?>">

<button type="submit" name="update_profile" class="btn">Update Profile</button>

</form>

</div>

<!-- PASSWORD -->
<div class="tabshow">

<h2>Change Password</h2>

<form method="post">

<input type="password" name="old_password" class="input" placeholder="Old Password" required>

<input type="password" name="new_password" class="input" placeholder="New Password" required>

<button type="submit" name="change_password" class="btn">Change Password</button>

</form>

</div>

</div>
</div>

<script>
function openTab(i){
    let tabs = document.querySelectorAll(".tab");
    let content = document.querySelectorAll(".tabshow");

    tabs.forEach(t => t.classList.remove("active"));
    content.forEach(c => c.classList.remove("active"));

    tabs[i].classList.add("active");
    content[i].classList.add("active");
}
</script>

</body>
</html>
