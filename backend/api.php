<?php
$username = 'root';
$host = 'localhost';
$db_name = 'taskgrid';
$pass = '';

session_start();
if(isset($_POST['login_id'])){
$_SESSION['login_id'] = $_POST['login_id'];
}
$login_id = 'U001';
$conn = new mysqli($host, $username, $pass, $db_name);

$action = $_POST['action'] ?? '';

switch($action){
    case 'readtasks':
        $sql = "
            SELECT ta.task_id, t.assigned_by, t.task_title, t.task_description, t.start_date, t.deadline, ta.status, ta.task_assignment_id
            FROM tasks t
            INNER JOIN taskassignment ta ON t.task_id = ta.task_id
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
        
    case "update_status":
        $sql = "UPDATE taskassignment set status= ? where task_assignment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $_POST['status'],$_POST['task_assignment_id']);
        $stmt->execute();
        echo json_encode(["message"=>"status changed successfully"]);
        break;
    
    case 'filterfaculty':
        $sql = 'SELECT name from users where dept = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $_POST['dept']);
        $stmt->execute();
        $res = $stmt->get_result();
        echo json_encode($res->fetch_all(MYSQLI_ASSOC));
        break;
    
    case 'forward':
        $userQuery = "SELECT user_id FROM users WHERE name = ?";
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

        $insertQuery = "INSERT INTO taskassignment (task_id, assigned_to, status, updated_at)
                        VALUES (?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('is', $task_id, $fwd_user_id);

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

        $stmt->close();
        exit;
       // break;
}
?>