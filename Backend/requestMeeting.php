<?php
header('Content-Type: application/json');
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "task_grid";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "DB Connection failed: " . $conn->connect_error
    ]));
}

// 🔹 Hardcode HOD ID here for testing (you can change it to HOD002 etc.)
$hardcodedUserId = 'HOD001';

// ✅ Fetch current HOD details from users table
$userQuery = $conn->prepare("SELECT name, department, role FROM users WHERE user_id = ?");
$userQuery->bind_param("s", $hardcodedUserId);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid HOD ID']);
    exit;
}

$userData = $userResult->fetch_assoc();
$hodName = $userData['name'];
$hodDept = $userData['department'];
$hodRole = $userData['role'];

// ✅ Fetch meeting requests made by this HOD
if (isset($_GET['fetchRequests'])) {
    $query = $conn->prepare("SELECT * FROM request_appointment WHERE request_by = ? ORDER BY request_time DESC");
    $query->bind_param("s", $hardcodedUserId);
    $query->execute();
    $result = $query->get_result();

    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = [
            'title' => strtoupper($row['request_by']),
            'staff' => "$hodName ($hodDept)",
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

    if (!$datetime || !$purpose) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $insert = $conn->prepare("INSERT INTO request_appointment (request_by, request_time, status) VALUES (?, ?, 'Pending')");
    $insert->bind_param("ss", $hardcodedUserId, $datetime);

    if ($insert->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insert failed.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>