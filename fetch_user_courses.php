<?php
include 'settings.php';

// Get the action and username from the query parameters
$action = $_GET['action'];
$username = $_GET['username'];

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error));
    $conn->close();
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

$table = $action . '_courses';
$sql = "SELECT courses.id, courses.title, courses.description FROM $table INNER JOIN courses ON $table.course_id = courses.id WHERE $table.user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error));
    $conn->close();
    exit();
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$courses = array();

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);

$stmt->close();
$conn->close();
?>
