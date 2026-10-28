<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // GET USER
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // PASSWORD CHECK
        if (password_verify($password, $user['password'])) {

            // SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // REDIRECT BY ROLE
            if ($role == "admin") {
                header("Location: admin_dashboard.php");
            }
            else if ($role == "instructor") {
                header("Location: instructor_dashboard.php");
            }
            else if ($role == "office_staff") {
                header("Location: office_staff_dashboard.php");
            }
            else if ($role == "attendance") {
                header("Location: attendance_dashboard.php");
            }

            exit();

        } else {
            echo "❌ Incorrect password";
        }

    } else {
        echo "❌ User not found or role mismatch";
    }

    $stmt->close();
}

$conn->close();
?>