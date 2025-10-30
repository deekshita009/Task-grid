<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "college_erp";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get logged-in HOD info
$hod_id = $_SESSION['user_id'] ?? '';
if (!$hod_id) {
    echo json_encode(["error"=>"Not logged in"]);
    exit;
}

$res = $conn->query("SELECT dept FROM Users WHERE user_id='$hod_id' AND role='HOD'");
if (!$res || $res->num_rows==0) {
    echo json_encode(["error"=>"Unauthorized"]);
    exit;
}
$hod_dept = $res->fetch_assoc()['dept'];

$faculty = isset($_GET['faculty']) ? trim($_GET['faculty']) : '';
$month   = isset($_GET['month']) ? trim($_GET['month']) : '';

function fetchRow($conn, $sql, $types = '', $params = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) { throw new Exception($conn->error); }
    if ($types) { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function fetchAll($conn, $sql, $types = '', $params = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) { throw new Exception($conn->error); }
    if ($types) { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}

try {
    // Shared WHERE clause for HOD department + filters
    $where = ['u.dept = ?'];
    $types = 's';
    $params = [$hod_dept];

    if ($faculty !== '') { $where[] = 'u.name = ?'; $types .= 's'; $params[] = $faculty; }
    if ($month   !== '') { $where[] = "DATE_FORMAT(t.deadline, '%Y-%m') = ?"; $types .= 's'; $params[] = $month; }
    $whereSql = 'WHERE ' . implode(' AND ', $where);

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
        "total_tasks"=>0, "completed"=>0, "pending"=>0, "in_progress"=>0, "overdue"=>0
    ];

    /* --------------- 2️⃣ FACULTY PERFORMANCE --------------- */
    $facultySql = "
        SELECT 
            u.name AS faculty_name,
            COUNT(ta.task_id) AS total_tasks,
            SUM(ta.status='Completed') AS completed_tasks,
            ROUND(100 * SUM(ta.status='Completed') / NULLIF(COUNT(ta.task_id),0),2) AS completion_percentage
        FROM Users u
        LEFT JOIN TaskAssignment ta ON ta.assigned_to = u.user_id
        LEFT JOIN Tasks t           ON t.task_id = ta.task_id
        WHERE u.dept = ?
        " . ($faculty ? " AND u.name = ?" : "") . ($month ? " AND DATE_FORMAT(t.deadline, '%Y-%m') = ?" : "") . "
        GROUP BY u.user_id, u.name
        ORDER BY u.name
    ";
    $fParams = [$hod_dept];
    if($faculty) $fParams[] = $faculty;
    if($month) $fParams[] = $month;
    $fTypes = 's' . ($faculty ? 's' : '') . ($month ? 's' : '');
    $facultyData = fetchAll($conn, $facultySql, $fTypes, $fParams);


   /* --------------- 3️⃣ DEMERITS --------------- */
$demeritSql = "
SELECT 
    u.name AS faculty_name,
    SUM(ta.status='Pending') AS pending_tasks,
    SUM(ta.status='In Progress' AND t.deadline < CURDATE()) AS delayed_submissions,
    SUM(ta.status='Pending' AND t.deadline < CURDATE()) AS missed_deadlines,
    COALESCE(cp.total_penalty,0) AS total_demerit_points
FROM Users u
LEFT JOIN TaskAssignment ta ON ta.assigned_to = u.user_id
LEFT JOIN Tasks t           ON t.task_id = ta.task_id
LEFT JOIN (
    SELECT user_id, SUM(penalty_point) AS total_penalty
    FROM CreditPenalty
    GROUP BY user_id
) cp ON cp.user_id = u.user_id
WHERE u.dept = ? AND u.role='FACULTY'
" . ($faculty ? " AND u.name = ?" : "") . "
GROUP BY u.user_id, u.name, cp.total_penalty
ORDER BY u.name
";
$fParams = [$hod_dept];
if($faculty) $fParams[] = $faculty;
$fTypes = 's' . ($faculty ? 's' : '');
$demeritData = fetchAll($conn, $demeritSql, $fTypes, $fParams);


    /* --------------- 4️⃣ MONTHLY TREND --------------- */
    $trendData = [];
    $months = [];
    for ($i=5;$i>=0;$i--) $months[] = date("Y-m", strtotime("-$i month"));

    $trendSql = "
        SELECT DATE_FORMAT(completed_date, '%Y-%m') AS month, COUNT(*) AS completed_count
        FROM Tasks t
        JOIN TaskAssignment ta ON ta.task_id = t.task_id
        JOIN Users u ON u.user_id = ta.assigned_to
        WHERE t.status='Completed' AND u.dept = ? AND DATE_FORMAT(completed_date, '%Y-%m') IN ('" . implode("','",$months) . "')
        GROUP BY DATE_FORMAT(completed_date, '%Y-%m')
    ";
    $stmt = $conn->prepare($trendSql);
    $stmt->bind_param("s",$hod_dept);
    $stmt->execute();
    $res = $stmt->get_result();
    $rawTrend = [];
    while($row = $res->fetch_assoc()) $rawTrend[$row['month']] = $row['completed_count'];
    $stmt->close();

    foreach($months as $m){
        $trendData[] = [
            "month" => date("M", strtotime($m."-01")),
            "completed_count" => $rawTrend[$m] ?? 0
        ];
    }

    echo json_encode([
        "summary"=>$summary,
        "faculty"=>$facultyData,
        "demerits"=>$demeritData,
        "trend"=>$trendData
    ], JSON_PRETTY_PRINT);

} catch(Throwable $e) {
    http_response_code(500);
    echo json_encode(["error"=>$e->getMessage()]);
} finally {
    $conn->close();
}
?>
