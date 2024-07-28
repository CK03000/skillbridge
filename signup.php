<?php
include 'settings.php';

$postData = file_get_contents("php://input");
$request = json_decode($postData, true);

$username = $request['username'];
$email = $request['email'];
$password = password_hash($request['password'], PASSWORD_DEFAULT);
// For simplicity, assign 'user' role by default
$role = 'user';

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error));
    $conn->close();
    exit();
}

$stmt->bind_param("ssss", $username, $email, $password, $role);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error signing up'));
}

$stmt->close();
$conn->close();
?>
