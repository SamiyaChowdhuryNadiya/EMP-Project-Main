<?php
include "connection.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];
$position = $_POST['position']; // IMPORTANT

$sql = "INSERT INTO users (name, email, password, role, position)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $name, $email, $password, $role, $position);

if($stmt->execute()){
    header("Location: login.html");
} else {
    echo "Error registering user";
}
?>
