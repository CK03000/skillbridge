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

$email = $request['email'];
$password = $request['password'];

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$sql = "SELECT id, username, password, role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Statement preparation failed: ' . $conn->error));
    $conn->close();
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $username, $hashedPassword, $role);
$stmt->fetch();

if (function_exists('password_verify')) {
    $isPasswordCorrect = password_verify($password, $hashedPassword);
} else {
    $isPasswordCorrect = hash_equals($hashedPassword, crypt($password, $hashedPassword));
}

if ($isPasswordCorrect) {
    echo json_encode(array('success' => true, 'message' => 'Login successful', 'username' => $username, 'role' => $role));
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid email or password'));
}

$stmt->close();
$conn->close();
?>
