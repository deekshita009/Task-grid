<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "taskgrid";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

// ✅ Fetch requests
if (isset($_GET['fetchRequests'])) {
    $query = "SELECT ra.request_id, u2.name AS requested_to, u1.name AS staff, ra.purpose, ra.request_time, ra.status
              FROM request_appointment ra
              JOIN users u1 ON ra.request_by = u1.user_id
              JOIN users u2 ON ra.requested_to = u2.user_id
              ORDER BY ra.request_time DESC";
    $result = $conn->query($query);
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = [
            'requested_to' => $row['requested_to'],
            'users' => $row['staff'],
            'purpose' => $row['purpose'],
            'request_time' => date('d M Y, h:i A', strtotime($row['request_time'])),
            'status' => $row['status']
        ];
    }
    echo json_encode(['success' => true, 'data' => $requests]);
    exit;
}

// ✅ Handle new meeting request (JSON input)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $request_by   = $data['request_by'] ?? '';
    $requested_to = $data['requested_to'] ?? '';
    $purpose      = $data['purpose'] ?? '';
    $request_time = $data['request_time'] ?? '';

    if (!$request_by || !$requested_to || !$purpose || !$request_time) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $insert = $conn->prepare("
        INSERT INTO request_appointment (request_by, requested_to, purpose, request_time, status)
        VALUES (?, ?, ?, ?, 'Pending')
    ");
    $insert->bind_param("ssss", $request_by, $requested_to, $purpose, $request_time);

    if ($insert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Meeting request submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'DB insert failed: ' . $conn->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
