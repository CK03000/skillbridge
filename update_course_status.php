<?php
include 'settings.php';

// Custom hash_equals function for compatibility with PHP < 5.6
if (!function_exists('hash_equals')) {
    function hash_equals($str1, $str2) {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}

// Get the raw POST data
$postData = file_get_contents("php://input");
$request = json_decode($postData, true);

$username = $request['username'];
$courseId = $request['course_id'];
$action = $request['action'];
$password = isset($request['password']) ? $request['password'] : null;

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "SELECT id, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error . ' (SQL: ' . $sql . ')'));
    $conn->close();
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userId, $hashedPassword);
$stmt->fetch();
$stmt->close();

if ($action == 'buy' && !function_exists('password_verify') && !hash_equals($hashedPassword, crypt($password, $hashedPassword))) {
    echo json_encode(array('success' => false, 'message' => 'Invalid password'));
    $conn->close();
    exit();
}

$table = '';
if ($action == 'like') {
    $table = 'liked_courses';
} elseif ($action == 'enroll') {
    $table = 'enrolled_courses';
} elseif ($action == 'buy') {
    $table = 'bought_courses';
}

// Check if the course is already in the table
$checkSql = "SELECT id FROM $table WHERE user_id = ? AND course_id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ii", $userId, $courseId);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(array('success' => false, 'message' => 'This course is already in there'));
    $checkStmt->close();
    $conn->close();
    exit();
}
$checkStmt->close();

$sql = "INSERT INTO $table (user_id, course_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error . ' (SQL: ' . $sql . ')'));
    $conn->close();
    exit();
}

$stmt->bind_param("ii", $userId, $courseId);

if ($stmt->execute()) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error executing statement: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>
