<?php
include 'settings.php';

// Get the raw POST data
$postData = file_get_contents("php://input");
$request = json_decode($postData, true);

$course_id = $request['id'];
$title = $request['title'];
$description = $request['description'];

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "UPDATE courses SET title = ?, description = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error));
    $conn->close();
    exit();
}

$stmt->bind_param("ssi", $title, $description, $course_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(array('success' => true, 'message' => 'Course updated successfully'));
} else {
    // Log the error and set a failure message
    error_log("Failed to update course with ID $course_id. Affected rows: " . $stmt->affected_rows);
    echo json_encode(array('success' => false, 'message' => 'Failed to update course'));
}

$stmt->close();
$conn->close();
?>
