<?php
$username = 'root';
$host = 'localhost';
$db_name = 'college_erp';
$pass = '';


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
$conn = new mysqli($host, $username, $pass, $db_name);

$action = $_POST['action'] ?? '';

$conn->query("
    UPDATE TaskAssignment ta
    INNER JOIN Tasks t ON ta.task_id = t.task_id
    SET ta.status = 'Overdue'
    WHERE ta.status != 'Completed'
      AND t.deadline < CURDATE()
    ");


//after deadline extension
$conn->query("
    UPDATE TaskAssignment ta
    INNER JOIN Tasks t ON ta.task_id = t.task_id
    SET ta.status = 'Pending'
    WHERE ta.status = 'Overdue'
      AND t.deadline > CURDATE()
    ");    

 

switch($action){
    case 'readtasks':
        $sql = "
            SELECT ta.task_id, t.assigned_by, t.task_title, t.task_description, t.start_date, t.deadline, ta.status, ta.task_assignment_id, ta.action
            FROM Tasks t
            INNER JOIN TaskAssignment ta ON t.task_id = ta.task_id
            WHERE ta.assigned_to = ?
        ";

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
    
    case "accept_task":
        $sql = "UPDATE taskassignment set status= 'Pending', action = 'In Progress' where task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i' ,$_POST['tid']);
        $stmt->execute();
        echo json_encode(["message"=>"Task accepted"]);
        break;

    case "update_status":
        $sql = "UPDATE TaskAssignment set status= ?, action = 'Done' where task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $_POST['status'],$_POST['task_assignment_id']);
        $stmt->execute();
        echo json_encode(["message"=>"status changed successfully"]);
        break;
    
    case 'filterfaculty':
        $sql = 'SELECT name from Users where dept = ? OR add_role = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $_POST['dept'],$_POST['dept']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;
    
    case 'forward':
        $userQuery = "SELECT user_id FROM Users WHERE name = ?";
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param('s', $_POST['name']);
        $stmt->execute();
        $result = $stmt->get_result();
        $fwdUser = $result->fetch_assoc();
        $stmt->close();

        if (!$fwdUser) {
            echo json_encode(['response' => 'error', 'message' => 'Faculty not found']);
            exit;
        }

        $fwd_user_id = $fwdUser['user_id'];
        $task_id = $_POST['taskId'];

        $insertQuery = "INSERT INTO TaskAssignment (task_id, parent_assig_id, assigned_to, status, updated_at)
                        VALUES (?, ?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('iss', $task_id, $login_id, $fwd_user_id);

        if ($stmt->execute()) {
            echo json_encode([
                'response' => 'success',
                'message' => 'Task forwarded successfully to ' . $_POST['name']
            ]);
        } else {
            echo json_encode([
                'response' => 'error',
                'message' => 'Failed to forward task: ' . $stmt->error
            ]);
        }
        //change action to forwarded
        $sql = "UPDATE TaskAssignment set action = 'task forwarded' where task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i',$_POST['taskAssId']);
        $stmt->execute();

        $stmt->close();
        exit;
        break;

    case 'requestdeadline':
        $sql = "INSERT into deadline_request (task_id, taskassignment_id, Reason, requested_deadline ,created_at) values(?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiss', $_POST['taskId'],$_POST['taskAssId'], $_POST['reason'], $_POST['Extdl']);
        if ($stmt->execute()) {
            echo json_encode([
                'response' => 'success',
                'message' => 'Request sent successfully'
            ]);
        } else {
            echo json_encode([
                'response' => 'error',
                'message' => 'Failed to forward task: ' . $stmt->error
            ]);
        }
        //change action to deadline requested
        $sql = "UPDATE TaskAssignment set action = 'deadline extension requested' where task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i',$_POST['taskAssId']);
        $stmt->execute();

        $stmt->close();
        exit;

    case "submitproof":
        $target_folder = "hod/uploads";
        $target_file = time().'_'.basename($_FILES['proof']['name']);
        $target_name = $target_folder.$target_file;
        if(!move_uploaded_file($_FILES['proof']['tmp_name'], $target_name)){
            echo json_encode(["message"=>"failed to upload file"]);
            exit;
        }
        $stmt = $conn->prepare("UPDATE taskassignment SET proof= ? , explanation = ?, action = 'Submitted' where task_id = ? and task_assignment_id = ?");
        $stmt->bind_param("ssss", $target_name, $_POST['exp'], $_POST['taskid'], $_POST['taskassid']);
        $stmt->execute();
        echo json_encode(["message"=>"Proof submitted"]);
        break;


}
?>