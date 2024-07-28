<?php
include 'settings.php';

$postData = file_get_contents("php://input");
$request = json_decode($postData, true);

$username = $request['username'];

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "SELECT courses.id, courses.title, courses.description FROM bought_courses 
        JOIN users ON bought_courses.user_id = users.id 
        JOIN courses ON bought_courses.course_id = courses.id 
        WHERE users.username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error));
    $conn->close();
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$boughtCourses = array();

while ($row = $result->fetch_assoc()) {
    $boughtCourses[] = $row;
}

echo json_encode($boughtCourses);

$stmt->close();
$conn->close();
?>
