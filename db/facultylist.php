<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli('localhost','root','','college_erp');
$hod_id = $_SESSION['user_id'] ?? '';
if(!$hod_id) exit(json_encode([]));

$res = $conn->query("SELECT dept FROM Users WHERE user_id='$hod_id' AND role='HOD'");
$dept = $res->fetch_assoc()['dept'] ?? '';

$data = [];
if($dept){
    $result = $conn->query("SELECT name FROM Users WHERE dept='$dept' AND role='FACULTY' ORDER BY name");
    while($row=$result->fetch_assoc()) $data[] = $row;
}

echo json_encode($data);
?>
