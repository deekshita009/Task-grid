<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOD') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}


error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "college_erp";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "DB Connection failed: " . $conn->connect_error
    ]));
}

// 🔹 Hardcode current STAFF user ID (you can replace with session later)
$currentUserId = $_SESSION['user_id'];



// ✅ Fetch current staff details
$userQuery = $conn->prepare("SELECT name, dept, role FROM Users WHERE user_id = ?");
$userQuery->bind_param("s", $currentUserId);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid User ID']);
    exit;
}

$userData = $userResult->fetch_assoc();
$staffName = $userData['name'];
$staffDept = $userData['dept'];
$staffRole = $userData['role'];

// ✅ Fetch meeting requests made BY this staff (to higher official)
if (isset($_GET['fetchRequests'])) {
    $query = $conn->prepare("SELECT * FROM Request_Appointment WHERE request_by = ? ORDER BY request_time DESC");
    $query->bind_param("s", $currentUserId);
    $query->execute();
    $result = $query->get_result();

    $requests = [];
    while ($row = $result->fetch_assoc()) {
        // Fetch higher official info
        $officialName = "-";
        if (!empty($row['request_to'])) {
            $getOfficial = $conn->prepare("SELECT name, role FROM Users WHERE user_id = ?");
            $getOfficial->bind_param("s", $row['request_to']);
            $getOfficial->execute();
            $offResult = $getOfficial->get_result();
            if ($offResult->num_rows > 0) {
                $official = $offResult->fetch_assoc();
                $officialName = "{$official['name']} ({$official['role']})";
            }
        }

        $requests[] = [
            'purpose' => $row['purpose'],
            'staff' => "$staffName ($staffDept)",
            'to' => $officialName,
            'datetime' => $row['request_time'] ? date('d M Y, h:i A', strtotime($row['request_time'])) : '-',
            'status' => $row['status'] ?? 'Pending'
        ];
    }

    echo json_encode(['success' => true, 'data' => $requests]);
    exit;
}

// ✅ Handle new meeting request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datetime = $_POST['datetime'] ?? null;
    $purpose = $_POST['purpose'] ?? '';
    $requestTo = $_POST['request_to'] ?? 'P001'; // Default to Principal (P001)

    if (!$datetime || !$purpose) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $insert = $conn->prepare("INSERT INTO Request_Appointment (request_by, requested_to, purpose, request_time, status) VALUES (?, ?, ?, ?, 'Pending')");
    $insert->bind_param("ssss", $currentUserId, $requestTo, $purpose, $datetime);

    if ($insert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Meeting request sent.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insert failed.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>