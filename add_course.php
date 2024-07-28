<?php
include 'settings.php';

// Get the raw POST data
$postData = file_get_contents("php://input");
$request = json_decode($postData, true);

$title = $request['title'];
$description = $request['description'];

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "INSERT INTO courses (title, description) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error));
    $conn->close();
    exit();
}

$stmt->bind_param("ss", $title, $description);

if ($stmt->execute()) {
    echo json_encode(array('success' => true, 'message' => 'Course added successfully'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error executing statement: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>
