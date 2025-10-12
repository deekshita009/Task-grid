<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$host = '10.0.252.162';
$username = 'team_user';
$password = "StrongP@sswOrd";
$dbname = 'team_db';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database Connection Failed']);
    exit();
}
$action = $_POST['action'] ?? '';
switch ($action) {
    //These are for Assigning to someone Table
    case 'read':
        readUsers($conn);
        break;
    case 'create':
        createUser($conn);
        break;
    case 'update':
        updateUser($conn);
        break;
    case 'delete':
        deleteUser($conn);
        break;
    //This for Faculty filter
    case 'facultynames':
        facultyfilter($conn);
        break;
    //This for Report data
    case 'getReportData':
        getReportData($conn);
        exit();
    default:
        echo json_encode(['error' => 'no operation are there']);
        break;
}
//Read for assigntoSomeone Tab
function readUsers($conn)
{
    try {
        $sql = "
        SELECT t.task_id,
         t.task_title,
          t.task_description, 
          t.start_date,
           t.deadline, 
           t.status, 
           u.name  FROM tasks 
           t LEFT JOIN users u 
           ON t.assigned_by = u.user_id ORDER BY t.task_id
        ";
        $result = $conn->query($sql);
        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        echo json_encode(['success' => true, 'data' => $users, 'message' => 'Readed Successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Not readed']);
    }
}

//Delete for assigntoSomeone Tab

function deleteUser($conn)
{
    try {

        // Delete from first table
        $sqldel = "DELETE from taskassignment where task_id=?";
        $checkdql = $conn->prepare($sqldel);
        $checkdql->bind_param('i', $_POST['id']);
        $checkdql->execute();

        // Then delete from parent table
        $sql = "DELETE from tasks WHERE task_id=?";
        $check = $conn->prepare($sql);
        $check->bind_param('i', $_POST['id']);
        if ($check->execute()) {
            echo json_encode(['success' => true, 'message' => 'successfully Deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete task']);
        }
    } catch (exception $e) {
        echo json_encode(['success' => false, 'message' => 'Exception ']);
    }

}

//Faculty filter code
function facultyfilter($conn)
{

    $sql = "SELECT ddept from users where ddept=?";
    $check = $conn->prepare($sql);
    $check->bind_param('s', $_POST['depart_id']);
    $check->execute();
    $st = $check->get_result();
    if ($st->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'No data']);
        return;
    }
    $fet = $st->fetch_assoc();
    $dept = $fet['ddept'];

    // fetch faculty details
    $sql = "SELECT user_id, name FROM users WHERE ddept=?";
    $facultyCheck = $conn->prepare($sql);
    $facultyCheck->bind_param('s', $dept);
    $facultyCheck->execute();
    $result = $facultyCheck->get_result();
    $faculties = [];
    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'no faculty found']);
        return;
    }
    while ($row = $result->fetch_assoc()) {
        $faculties[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $faculties, 'message' => 'faculty read successfully']);
}

// Create task
function createUser($conn)
{
    //user name 
    $sql = "INSERT INTO tasks (assigned_by,task_title,task_description,start_date,deadline,status) values(?,?,?,?,?,?)";
    $check = $conn->prepare($sql);
    $check->bind_param('ssssss', $_POST['assigned_by'], $_POST['task_title'], $_POST['task_description'], $_POST['start_date'], $_POST['deadline'], $_POST['status']);
    if ($check->execute()) {
        $task_id = $conn->insert_id;
        $asssql = "INSERT INTO taskassignment (task_id,assigned_to,parent_assig_id,status,updated_at) values(?,?,?,?,NOW())";
        $checkassql = $conn->prepare($asssql);
        $parent = null;
        $checkassql->bind_param('isis', $task_id, $_POST['assigned_by'], $parent, $_POST['status']);
        if ($checkassql->execute()) {
            echo json_encode(['success' => true, 'message' => 'Created Successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Task created but assignment failed: ' . $checkassql->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'not Created']);
    }
}

//Update function
function updateUser($conn)
{
    try {
        $sql = "UPDATE tasks SET task_title=?, task_description=?, start_date=?, deadline=?, status=? WHERE task_id=?";
        $check = $conn->prepare($sql);
        $check->bind_param('sssssi', $_POST['task_title'], $_POST['task_description'], $_POST['start_date'], $_POST['deadline'], $_POST['status'], $_POST['task_id']);
        if ($check->execute()) {
            $newtable = "UPDATE taskassignment SET parent_assig_id=null,status='pending',updated_at=NOW() WHERE task_id=?";
            $checksqll = $conn->prepare($newtable);
            $checksqll->bind_param('i', $_POST['task_id']);
            if ($checksqll->execute()) {
                echo json_encode(['success' => true, 'message' => 'Updated Successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Task updated but assignment update failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update task: ' . $check->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e]);
    }
}
//Shivani module
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


//shivani module

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







//vishva priya module

$faculty = isset($_GET['faculty']) ? trim($_GET['faculty']) : '';
$month = isset($_GET['month']) ? trim($_GET['month']) : '';

function fetchRow($conn, $sql, $types = '', $params = [])
{
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception($conn->error);
    }
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function fetchAll($conn, $sql, $types = '', $params = [])
{
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception($conn->error);
    }
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}

try {
    // Shared WHERE for summary + demerits
    $where = [];
    $types = '';
    $params = [];

    if ($faculty !== '') {
        $where[] = 'u.name = ?';
        $types .= 's';
        $params[] = $faculty;
    }
    if ($month !== '') {
        $where[] = "DATE_FORMAT(t.deadline, '%Y-%m') = ?";
        $types .= 's';
        $params[] = $month;
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    /* --------------- 1️⃣ SUMMARY --------------- */
    $summarySql = "
        SELECT
            COUNT(*) AS total_tasks,
            SUM(ta.status='Completed') AS completed,
            SUM(ta.status='Pending') AS pending,
            SUM(ta.status='In Progress') AS in_progress,
            SUM(ta.status <> 'Completed' AND t.deadline < CURDATE()) AS overdue
        FROM TaskAssignment ta
        JOIN Tasks t  ON t.task_id = ta.task_id
        JOIN Users u  ON u.user_id = ta.assigned_to
        $whereSql
    ";
    $summary = fetchRow($conn, $summarySql, $types, $params) ?? [
        "total_tasks" => 0,
        "completed" => 0,
        "pending" => 0,
        "in_progress" => 0,
        "overdue" => 0
    ];

    /* --------------- 2️⃣ FACULTY PERFORMANCE --------------- */
    $fpTypes = '';
    $fpParams = [];
    $facultyWhere = '';
    $monthOn = '';

    if ($faculty !== '') {
        $facultyWhere = 'WHERE u.name = ?';
        $fpTypes .= 's';
        $fpParams[] = $faculty;
    }
    if ($month !== '') {
        $monthOn = " AND DATE_FORMAT(t.deadline, '%Y-%m') = ?";
        $fpTypes .= 's';
        $fpParams[] = $month;
    }

    $facultySql = "
        SELECT 
            u.name AS faculty_name,
            COUNT(ta.task_id) AS total_tasks,
            SUM(ta.status='Completed') AS completed_tasks,
            ROUND(100 * SUM(ta.status='Completed') / NULLIF(COUNT(ta.task_id), 0), 2) AS completion_percentage
        FROM Users u
        LEFT JOIN TaskAssignment ta ON ta.assigned_to = u.user_id
        LEFT JOIN Tasks t           ON t.task_id = ta.task_id $monthOn
        $facultyWhere
        GROUP BY u.user_id, u.name
        ORDER BY u.name
    ";
    $facultyData = fetchAll($conn, $facultySql, $fpTypes, $fpParams);

    /* --------------- 3️⃣ DEMERITS --------------- */
    $demeritSql = "
        SELECT 
            u.name AS faculty_name,
            SUM(ta.status='Pending') AS pending_tasks,
            SUM(ta.status='In Progress' AND t.deadline < CURDATE()) AS delayed_submissions,
            SUM(ta.status='Pending' AND t.deadline < CURDATE()) AS missed_deadlines,
            COALESCE(cp.total_penalty, 0) AS total_demerit_points
        FROM Users u
        LEFT JOIN TaskAssignment ta ON ta.assigned_to = u.user_id
        LEFT JOIN Tasks t           ON t.task_id = ta.task_id
        LEFT JOIN (
            SELECT user_id, SUM(penalty_point) AS total_penalty
            FROM CreditPenalty
            GROUP BY user_id
        ) cp ON cp.user_id = u.user_id
        $whereSql
        GROUP BY u.user_id, u.name, cp.total_penalty
        ORDER BY u.name
    ";
    $demeritData = fetchAll($conn, $demeritSql, $types, $params);

    /* --------------- 4️⃣ MONTHLY TREND (Past 6 Months) --------------- */
    $trendData = [];

    // Generate last 6 months labels
    $months = [];
    for ($i = 5; $i >= 0; $i--) {
        $months[] = date("Y-m", strtotime("-$i month"));
    }

    // Prepare query
    $trendSql = "
        SELECT DATE_FORMAT(completed_date, '%Y-%m') AS month, COUNT(*) AS completed_count
        FROM Tasks
        WHERE status='Completed'
          AND DATE_FORMAT(completed_date, '%Y-%m') IN ('" . implode("','", $months) . "')
        GROUP BY DATE_FORMAT(completed_date, '%Y-%m')
    ";

    $result = $conn->query($trendSql);
    $rawTrend = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rawTrend[$row['month']] = $row['completed_count'];
        }
    }

    // Fill trendData with all 6 months, zero if no data
    foreach ($months as $m) {
        $trendData[] = [
            "month" => date("M", strtotime($m . "-01")),
            "completed_count" => isset($rawTrend[$m]) ? (int) $rawTrend[$m] : 0
        ];
    }

    /* ✅ Final JSON Response */
    echo json_encode([
        "summary" => $summary,
        "faculty" => $facultyData,
        "demerits" => $demeritData,
        "trend" => $trendData
    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

// Report data function
function getReportData($conn)
{
    try {
        // Summary data
        $summary = [];
        $sql = "SELECT 
                    COUNT(*) as total_tasks,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in-progress' THEN 1 ELSE 0 END) as in_progress,
                    SUM(CASE WHEN deadline < CURDATE() AND status != 'completed' THEN 1 ELSE 0 END) as overdue
                FROM tasks";
        $result = $conn->query($sql);
        $summary = $result->fetch_assoc();

        // Faculty data
        $facultyData = [];
        $sql = "SELECT u.name as faculty_name, 
                       COUNT(t.task_id) as total_tasks,
                       SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                       ROUND((SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) / COUNT(t.task_id)) * 100, 2) as completion_percentage
                FROM users u 
                LEFT JOIN tasks t ON u.user_id = t.assigned_by 
                GROUP BY u.user_id, u.name";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $facultyData[] = $row;
        }

        // Demerits data
        $demeritData = [];
        $sql = "SELECT u.name as faculty_name,
                       SUM(CASE WHEN t.status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
                       SUM(CASE WHEN t.deadline < CURDATE() AND t.status != 'completed' THEN 1 ELSE 0 END) as delayed_submissions,
                       SUM(CASE WHEN t.deadline < CURDATE() AND t.status != 'completed' THEN 1 ELSE 0 END) as missed_deadlines,
                       SUM(CASE WHEN t.deadline < CURDATE() AND t.status != 'completed' THEN 2 ELSE 0 END) as total_demerit_points
                FROM users u 
                LEFT JOIN tasks t ON u.user_id = t.assigned_by 
                GROUP BY u.user_id, u.name";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $demeritData[] = $row;
        }

        // Trend data (last 6 months)
        $trendData = [];
        $sql = "SELECT DATE_FORMAT(start_date, '%Y-%m') as month,
                       COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_count
                FROM tasks 
                WHERE start_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(start_date, '%Y-%m')
                ORDER BY month";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $trendData[] = $row;
        }

        echo json_encode([
            "summary" => $summary,
            "faculty" => $facultyData,
            "demerits" => $demeritData,
            "trend" => $trendData
        ]);
        return;

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        return;
    }
}
?>