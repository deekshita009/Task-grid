<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'college_erp';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in and role is HOD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOD') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$login_id = $_SESSION['user_id'];
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database Connection Failed']);
    exit();
}

$conn->query("
    UPDATE TaskAssignment ta
    INNER JOIN Tasks t ON ta.task_id = t.task_id
    SET ta.status = 'Overdue'
    WHERE ta.status != 'Completed'
      AND t.deadline < CURDATE()
    ");

$action = $_POST['action'] ?? '';

switch ($action) {

    case "readAssignedTasks":
        $sql = "SELECT t.task_id, ta.task_assignment_id, ta.assigned_to, t.task_title, t.task_description, t.start_date, t.deadline, ta.status, ta.action
                FROM tasks t INNER JOIN taskassignment ta ON t.task_id = ta.task_id WHERE t.assigned_by = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $tasks = [];
        while ($row = $res->fetch_assoc()) {
            $tasks[] = $row;
        }

        echo json_encode($tasks);
        break;

    case 'filterfaculty':
        $sql = 'SELECT name from users where dept = ? OR add_role = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $_POST['dept'],$_POST['dept']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;

    case 'assigntasks':

        $faculties = $_POST['fac']; 
        $dept = $_POST['dept'];
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $start = $_POST['date'];
        $deadline = $_POST['deadline'];

        $insertTask = "INSERT INTO tasks (assigned_by, task_title, task_description, start_date, deadline, status)
                   VALUES (?, ?, ?, ?, ?, 'Assigned')";
        $stmt = $conn->prepare($insertTask);
        $stmt->bind_param('sssss', $login_id, $title, $desc, $start, $deadline);
    
        if (!$stmt->execute()) {
            echo json_encode(['response' => 'error', 'message' => 'Failed to insert task']);
            exit;
        }
        $stmt->close();

        // Get the newly inserted task ID
        $task_id = $conn->insert_id;

        // 2. Assign to one or more faculties
        $userQuery = "SELECT user_id FROM users WHERE name = ? AND dept = ?";
        $stmtUser = $conn->prepare($userQuery);

        $insertAssign = "INSERT INTO taskassignment (task_id, assigned_to, parent_assig_id, status, deadline, updated_at, action)
                     VALUES (?, ?, ?, 'Assigned',$deadline, CURDATE(), 'In Progress')";
        $stmtAssign = $conn->prepare($insertAssign);

        $successCount = 0;
        $failedCount = 0;

        foreach ($faculties as $faculty) {
            //  Fetch faculty user_id
            $stmtUser->bind_param('ss', $faculty, $dept);
            $stmtUser->execute();
            $result = $stmtUser->get_result();
            $assUser = $result->fetch_assoc();

            if (!$assUser) {
                $failedCount++;
                continue; // Skip if faculty not found
            }

            $ass_user_id = $assUser['user_id'];

            // Insert into taskassignment
            $stmtAssign->bind_param('iss', $task_id, $ass_user_id, $login_id);
            if ($stmtAssign->execute()) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        $stmtUser->close();
        $stmtAssign->close();

        // Send response
        if ($successCount > 0) {
            echo json_encode([
                'response' => 'success',
                'message' => "Task assigned to {$successCount} faculty member(s)."
            ]);
        } else {
            echo json_encode([
                'response' => 'error',
                'message' => 'No valid faculty found or task assignment failed.'
            ]);
        }

        exit;
        break;


    // GET single task details by ID
    case "fetchrow":
        $sql = "SELECT t.task_id, ta.task_assignment_id, ta.assigned_to, t.task_title, t.task_description, t.start_date, t.deadline, ta.assigned_to, u.name, u.ddept
                FROM tasks t INNER JOIN taskassignment ta ON t.task_id = ta.task_id LEFT JOIN users u ON ta.assigned_to = u.user_id WHERE ta.task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_assoc());
        break;


    // EDIT task
    case "editTask":
        $sql = "UPDATE tasks 
                SET task_title = ?, task_description = ?, start_date = ?, deadline = ?, status = 'Pending'
                WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $_POST['title'],
            $_POST['description'],
            $_POST['startdate'],
            $_POST['deadline'],
            $_POST['id']
        );
        $stmt->execute();
        echo json_encode(["response" => "success", "message" => "Task updated successfully"]);
        break;


    // DELETE task
    case "deletetask":
        $count = "SELECT COUNT(*) as cnt from taskassignment WHERE task_id = ?";
        $stmt = $conn->prepare($count);
        $stmt->bind_param('i', $_POST['taskid']);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        $stmt->close();
        
        $sql = "DELETE FROM taskassignment WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['taskAsid']);
        $stmt->execute();
        $stmt->close();

        if($res['cnt'] == 1){
            $sql = "DELETE FROM tasks WHERE task_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_POST['taskid']);
            $stmt->execute();
            
        }
        $stmt->close();
        echo json_encode(["response" => "success", "message" => "Task deleted successfully"]);
        break;

    case 'getdlreq':
        $sql = "SELECT dr_id, taskassignment_id, requested_deadline, Reason FROM deadline_request WHERE taskassignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['tid']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_assoc());
        break;

    case 'getsub':
        $sql = "SELECT task_assignment_id, proof, explanation FROM taskassignment WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['tId']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_assoc());
        break;

    case "approvereqdl":
        $sql = "UPDATE deadline_request dr INNER JOIN taskassignment ta ON dr.taskassignment_id = ta.task_assignment_id SET dr.status = 'approved', ta.action = 'deadline extension approved', ta.deadline = ? WHERE dr.dr_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $_POST['extndl'], $_POST['drId']);
    
        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Deadline extension approved"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to approve request"]);
        }
        break;

    case "rejectreqdl":
        $sql = "UPDATE deadline_request dr INNER JOIN taskassignment ta ON dr.taskassignment_id = ta.task_assignment_id SET dr.status = 'approved', ta.action = 'deadline extension rejected' WHERE dr.dr_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['drId']); // Changed from taskId to drId
    
        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Deadline extension rejected"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to reject request"]);
        }
        break;
    
    case "approvesub":
        $sql = "UPDATE taskassignment  SET status = 'Completed', action = 'Submission approved' WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['tai']);
    
        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Submission approved"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to approve submission"]);
        }
        break;

    case "rejectsub":
        $sql = "UPDATE taskassignment  SET status = 'Pending', action = 'Submission rejected' WHERE task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['TaI']); // Changed from taskId to drId
    
        if ($stmt->execute()) {
            echo json_encode(["response" => "success", "message" => "Submission rejected"]);
        } else {
            echo json_encode(["response" => "error", "message" => "Failed to reject submission"]);
        }
        break;


    default:
        echo json_encode(["message" => "Invalid action"]);
}

?>
