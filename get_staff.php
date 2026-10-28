<?php
header('Content-Type: application/json');
include "connection.php";

if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "msg" => "No ID"]);
    exit();
}

$id = intval($_GET['id']);

/* SAFE QUERY */
$stmt = $conn->prepare("SELECT name, position FROM users WHERE id=?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "msg" => "Query failed"]);
    exit();
}

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    echo json_encode([
        "success" => true,
        "name" => $row['name'],
        "position" => $row['position']
    ]);

} else {
    echo json_encode([
        "success" => false,
        "msg" => "User not found"
    ]);
}
?>
