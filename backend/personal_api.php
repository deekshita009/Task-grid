<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$database = "taskgrid";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
  case 'fetch':
    $result = $conn->query("SELECT * FROM personal");
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
    }
    echo json_encode($tasks);
    break;

  case 'add':
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start = $_POST['startDate'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $sql = "INSERT INTO personal (title, description, start_date, deadline, priority)
            VALUES ('$title', '$description', '$start', '$deadline', '$priority')";
    echo $conn->query($sql) ? "success" : "error";
    break;

  case 'update':
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start = $_POST['startDate'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $sql = "UPDATE personal SET 
            title='$title', description='$description', start_date='$start',
            deadline='$deadline', priority='$priority', status='$status'
            WHERE id=$id";
    echo $conn->query($sql) ? "success" : "error";
    break;

  case 'delete':
    $id = $_POST['id'];
    $sql = "DELETE FROM personal WHERE id=$id";
    echo $conn->query($sql) ? "success" : "error";
    break;

  default:
    echo "Invalid action";
    break;
}

$conn->close();
?>
