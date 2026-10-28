<?php
session_start();
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!file_exists("uploads")) {
    mkdir("uploads");
}

/* GET USER */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* UPDATE PROFILE */
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];

    $profile_image = $user['profile_image'] ?? null;

    /* IMAGE UPLOAD FIXED */
    if (!empty($_FILES['profile_image']['name'])) {

        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $profile_image = time() . "." . $ext;

        move_uploaded_file(
            $_FILES['profile_image']['tmp_name'],
            "uploads/" . $profile_image
        );
    }

    $stmt = $conn->prepare("
        UPDATE users 
        SET name=?, email=?, phone=?, address=?, position=?, department=?, gender=?, dob=?, profile_image=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "sssssssssi",
        $name,
        $email,
        $phone,
        $address,
        $position,
        $department,
        $gender,
        $dob,
        $profile_image,
        $user_id
    );

    $stmt->execute();

    header("Location: profile.php");
    exit();
}

/* PASSWORD CHANGE */
if (isset($_POST['change_password'])) {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    if (password_verify($old_password, $user['password'])) {

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();

        echo "<script>alert('Password updated successfully');</script>";

    } else {
        echo "<script>alert('Old password is incorrect');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<style>
body{font-family:Arial;background:#f4f7fb;}
.container{width:60%;margin:auto;margin-top:30px;background:white;padding:25px;border-radius:10px;}

.profile-image{text-align:center;margin-bottom:20px;}
.profile-image img{
    width:130px;height:130px;border-radius:50%;
    object-fit:cover;border:4px solid #6366f1;
}

input,select{
    width:100%;padding:10px;margin:8px 0 15px;
    border:1px solid #ddd;border-radius:6px;
}

button{
    padding:10px 15px;background:#6366f1;
    color:white;border:none;border-radius:6px;
}
</style>

</head>

<body>

<div class="container">

<h1>Profile Settings</h1>

<div class="profile-image">

<?php if (!empty($user['profile_image'])) { ?>
    <img src="uploads/<?php echo $user['profile_image']; ?>">
<?php } else { ?>
    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png">
<?php } ?>

</div>

<form method="post" enctype="multipart/form-data">

<h2>Personal Info</h2>

<input type="file" name="profile_image">

<input type="text" name="name" value="<?php echo $user['name'] ?? ''; ?>">
<input type="email" name="email" value="<?php echo $user['email'] ?? ''; ?>">
<input type="text" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">

<select name="gender">
    <option value="Male" <?php if(($user['gender'] ?? '')=="Male") echo "selected"; ?>>Male</option>
    <option value="Female" <?php if(($user['gender'] ?? '')=="Female") echo "selected"; ?>>Female</option>
</select>

<input type="date" name="dob" value="<?php echo $user['dob'] ?? ''; ?>">

<h2>Work Info</h2>

<input type="text" name="position" value="<?php echo $user['position'] ?? ''; ?>">
<input type="text" name="department" value="<?php echo $user['department'] ?? ''; ?>">
<input type="text" name="address" value="<?php echo $user['address'] ?? ''; ?>">

<button name="update">Save Profile</button>

</form>

<h2>Change Password</h2>

<form method="post">

<input type="password" name="old_password" placeholder="Old Password">
<input type="password" name="new_password" placeholder="New Password">

<button name="change_password">Update Password</button>

</form>

</div>

</body>
</html>