<?php
include 'settings.php';

$conn = new mysqli($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, description FROM courses";
$result = $conn->query($sql);

$courses = array();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $courses[] = $row;
  }
} 

$conn->close();

echo json_encode($courses);
?>
