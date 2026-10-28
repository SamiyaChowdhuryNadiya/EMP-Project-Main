<?php
session_start();
include "connection.php";

if($_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

if(isset($_POST['submit'])){

    $stmt = $conn->prepare("INSERT INTO tasks 
    (taskID,title,description,assigned_to,email,building,shift,entry,exit_time)
    VALUES (?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param(
        "sssssssss",
        $_POST['taskID'],
        $_POST['title'],
        $_POST['description'],
        $_POST['assigned_to'],
        $_POST['email'],
        $_POST['building'],
        $_POST['shift'],
        $_POST['entry'],
        $_POST['exit_time']
    );

    $stmt->execute();

    echo "Task Assigned!";
}
?>

<h2>Assign Task</h2>

<form method="POST">

<input name="taskID" placeholder="TSK001"><br>
<input name="title" placeholder="Title"><br>
<textarea name="description"></textarea><br>

<input name="assigned_to" placeholder="STF001"><br>
<input name="email" placeholder="Email"><br>

<input name="building" placeholder="Building"><br>

<select name="shift">
    <option>Morning</option>
    <option>Evening</option>
    <option>Night</option>
</select><br>

<input type="time" name="entry"><br>
<input type="time" name="exit_time"><br>

<button name="submit">Assign</button>

</form>
