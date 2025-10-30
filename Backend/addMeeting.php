<?php
header('Content-Type: application/json');

// Database connection
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

// Debug logs
error_log("=== MEETING API CALL ===");
error_log("POST Data: " . print_r($_POST, true));
error_log("GET Data: " . print_r($_GET, true));

// Hardcoded HOD user ID (for testing)
$ph_user_id = '5';
$action = $_POST['action'] ?? '';

// 🟢 CASE 0: Filter faculty
if ($action == 'filterfaculty') {
    $sql = 'SELECT name, email FROM users WHERE dept = ? OR add_role = ?';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('ss', $_POST['dept'], $_POST['dept']);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
    $stmt->close();
    $conn->close();
    exit;
}

// 🟢 CASE 1: Add new meeting (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($action != 'filterfaculty')) {
    $title = $_POST['title'];
    $agenda = $_POST['agenda'];
    $fac_name = $_POST['fac_name'];
    $datetime = $_POST['datetime'];
    $mode = $_POST['mode'];
    $location = $_POST['location'];

    // Fetch faculty email
    $stmt = $conn->prepare("SELECT email FROM users WHERE name = ? LIMIT 1");
    $stmt->bind_param('s', $fac_name);
    $stmt->execute();
    $res = $stmt->get_result();
    $fac_email = ($res && $res->num_rows > 0) ? $res->fetch_assoc()['email'] : '';
    $stmt->close();

    $event_title = $title . " (" . $mode . ")";
    $event_description = "Agenda: $agenda\nLocation/Link: $location\nDate & Time: $datetime\nFaculty: $fac_name\nEmail: $fac_email";

    $stmt = $conn->prepare("INSERT INTO calendar (user_id, event_title, event_description) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $ph_user_id, $event_title, $event_description);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Meeting scheduled successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// 🟡 CASE 2: Fetch staff list (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['fetchMeetings'])) {
    $dept_result = $conn->query("SELECT dept FROM users WHERE user_id = '$ph_user_id'");
    $department = ($dept_result && $dept_result->num_rows > 0) ? $dept_result->fetch_assoc()['dept'] : '';

    $stmt = $conn->prepare("SELECT user_id, name, email FROM users WHERE dept = ? AND role = 'Staff'");
    $stmt->bind_param('s', $department);
    $stmt->execute();
    $res = $stmt->get_result();

    $staffs = $res->fetch_all(MYSQLI_ASSOC);
    echo json_encode($staffs);

    $stmt->close();
    $conn->close();
    exit;
}

// CASE 3: Fetch meeting records
if (isset($_GET['fetchMeetings'])) {
    $query = "SELECT c.calendar_id, c.event_title as title, 
              SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Faculty: ', -1), '\n', 1) as fac_name,
              SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Date & Time: ', -1), '\n', 1) as datetime,
              SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Location/Link: ', -1), '\n', 1) as location,
              u.email 
              FROM calendar c
              LEFT JOIN users u ON SUBSTRING_INDEX(SUBSTRING_INDEX(c.event_description, 'Faculty: ', -1), '\n', 1) = u.name 
              WHERE c.user_id = '$ph_user_id'
              ORDER BY c.calendar_id DESC";
    
    $result = mysqli_query($conn, $query);
    $meetings = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $meetings[] = $row;
    }
    
    echo json_encode($meetings);
    exit;
}

// 🔵 CASE 4: Update meeting
if (isset($_GET['updateMeeting'])) {
    $calendar_id = $_POST['calendar_id'];
    $title = trim($_POST['title']);
    $agenda = trim($_POST['agenda']);
    $datetime = trim($_POST['datetime']);
    $mode = trim($_POST['mode']);
    $location = trim($_POST['location']);

    $event_title = $title . " (" . $mode . ")";

    // Get existing meeting
    $stmt = $conn->prepare("SELECT event_description FROM calendar WHERE calendar_id = ?");
    $stmt->bind_param('i', $calendar_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $participants_info = "\nParticipants: All Staff";

    if ($res && $res->num_rows > 0) {
        $existing_desc = $res->fetch_assoc()['event_description'];
        foreach (explode("\n", $existing_desc) as $line) {
            if (strpos(trim($line), 'Participants:') === 0) {
                $participants_info = "\n" . trim($line);
                break;
            }
        }
    }
    $stmt->close();

    $event_description = "Agenda: $agenda\nLocation/Link: $location\nDate & Time: $datetime\nMode: $mode" . $participants_info;

    $stmt = $conn->prepare("UPDATE calendar SET event_title = ?, event_description = ? WHERE calendar_id = ?");
    $stmt->bind_param('ssi', $event_title, $event_description, $calendar_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Meeting updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

// In your fetchMeetings query
$query = "SELECT m.*, u.email 
          FROM meetings m 
          LEFT JOIN users u ON m.fac_name = u.name 
          WHERE ...";  // Your existing WHERE conditions
