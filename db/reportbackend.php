<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "task_grid";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

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
    // Shared WHERE for summary + demerits
    $where = [];
    $types = '';
    $params = [];

    if ($faculty !== '') { $where[] = 'u.name = ?'; $types .= 's'; $params[] = $faculty; }
    if ($month   !== '') { $where[] = "DATE_FORMAT(t.deadline, '%Y-%m') = ?"; $types .= 's'; $params[] = $month; }
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
        "total_tasks" => 0, "completed" => 0, "pending" => 0, "in_progress" => 0, "overdue" => 0
    ];

    /* --------------- 2️⃣ FACULTY PERFORMANCE --------------- */
    $fpTypes = '';
    $fpParams = [];
    $facultyWhere = '';
    $monthOn = '';

    if ($faculty !== '') { $facultyWhere = 'WHERE u.name = ?'; $fpTypes .= 's'; $fpParams[] = $faculty; }
    if ($month   !== '') { $monthOn = " AND DATE_FORMAT(t.deadline, '%Y-%m') = ?"; $fpTypes .= 's'; $fpParams[] = $month; }

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
            "month" => date("M", strtotime($m."-01")),
            "completed_count" => isset($rawTrend[$m]) ? (int)$rawTrend[$m] : 0
        ];
    }

    /* ✅ Final JSON Response */
    echo json_encode([
        "summary"  => $summary,
        "faculty"  => $facultyData,
        "demerits" => $demeritData,
        "trend"    => $trendData
    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    $conn->close();
}