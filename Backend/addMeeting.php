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

$hod_user_id = 'HOD001'; // Hardcoded temporarily for HOD login

// 🟢 CASE 1: Add new meeting (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $agenda = $_POST['agenda'];
    $staff = $_POST['staff'];
    $datetime = $_POST['datetime'];
    $mode = $_POST['mode'];
    $location = $_POST['location'];

    // Get department of HOD
    $dept_query = "SELECT department FROM users WHERE user_id = '$hod_user_id'";
    $dept_result = mysqli_query($conn, $dept_query);

    if (!$dept_result || mysqli_num_rows($dept_result) == 0) {
        echo json_encode(["success" => false, "message" => "HOD not found"]);
        exit;
    }

    $department = mysqli_fetch_assoc($dept_result)['department'];
    $event_title = $title . " (" . $mode . ")";
    $event_description = "Agenda: $agenda\nLocation/Link: $location\nDate & Time: $datetime";

    if ($staff === "Everyone") {
        $sql = "INSERT INTO calendar (task_id, user_id, event_title, event_description)
                SELECT NULL, user_id, '$event_title', '$event_description'
                FROM users
                WHERE department = '$department' AND role = 'Staff'";
    } else {
        $sql = "INSERT INTO calendar (task_id, user_id, event_title, event_description)
                VALUES (NULL, '$staff', '$event_title', '$event_description')";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
    }

    mysqli_close($conn);
    exit;
}

// 🟡 CASE 2: Fetch staff list (GET)
if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['fetchMeetings'])) {
    $dept_query = "SELECT department FROM users WHERE user_id = '$hod_user_id'";
    $dept_result = mysqli_query($conn, $dept_query);
    $department = '';

    if ($dept_result && mysqli_num_rows($dept_result) > 0) {
        $department = mysqli_fetch_assoc($dept_result)['department'];
    }

    $sql = "SELECT user_id, name FROM users WHERE department = '$department' AND role = 'Staff'";
    $result = mysqli_query($conn, $sql);
    $staffs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $staffs[] = $row;
    }

    echo json_encode($staffs);
    mysqli_close($conn);
    exit;
}

// 🟣 CASE 3: Fetch meeting records
if (isset($_GET['fetchMeetings'])) {
    $dept_query = "SELECT department FROM users WHERE user_id = '$hod_user_id'";
    $dept_result = mysqli_query($conn, $dept_query);
    $department = mysqli_fetch_assoc($dept_result)['department'];

    $sql = "
        SELECT 
            u.name AS staff_name,
            c.event_title,
            c.event_description
        FROM calendar c
        JOIN users u ON c.user_id = u.user_id
        WHERE u.department = '$department'
        ORDER BY c.task_id DESC
    ";

    $result = mysqli_query($conn, $sql);
    $meetings = [];

    while ($row = mysqli_fetch_assoc($result)) {
        // ✅ Correct newline splitting
        $desc = array_map('trim', explode("\n", $row['event_description']));

        // Extract fields
        $agenda = str_replace('Agenda: ', '', $desc[0] ?? '-');
        $location = str_replace('Location/Link: ', '', $desc[1] ?? '-');
        $datetime = str_replace('Date & Time: ', '', $desc[2] ?? '-');

        // ✅ Format datetime nicely if valid
        $formatted_datetime = $datetime;
        $timestamp = strtotime($datetime);
        if ($timestamp !== false) {
            $formatted_datetime = date("d M Y, h:i A", $timestamp);
        }

        $meetings[] = [
            "title" => $row['event_title'],
            "staff" => $row['staff_name'],
            "agenda" => $agenda,
            "location" => $location,
            "datetime" => $formatted_datetime
        ];
    }

    echo json_encode($meetings);
    mysqli_close($conn);
    exit;
}

?>