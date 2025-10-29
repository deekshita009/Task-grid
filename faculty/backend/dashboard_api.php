<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$host = 'localhost';
$dbname = 'college_erp';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Completed tasks count
$sqlCompleted = "SELECT COUNT(*) as completed FROM TaskAssignment WHERE TRIM(assigned_to) = ? AND status = 'Completed'";
$stmt = $conn->prepare($sqlCompleted);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$completed = $result->fetch_assoc()['completed'];

// Pending tasks count
$sqlPending = "SELECT COUNT(*) as pending FROM TaskAssignment WHERE TRIM(assigned_to) = ? AND status IN ('Pending','In Progress')";
$stmt = $conn->prepare($sqlPending);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pending = $result->fetch_assoc()['pending'];

// Demerit points
$sqlDemerit = "SELECT demerit_points FROM Users WHERE TRIM(user_id) = ?";
$stmt = $conn->prepare($sqlDemerit);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$demerit = $result->fetch_assoc()['demerit_points'];

echo json_encode([
    'completed' => (int)$completed,
    'pending' => (int)$pending,
    'demerit' => (int)$demerit
]);
?>
