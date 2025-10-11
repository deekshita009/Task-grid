<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$username = 'root';
$password = "";
$dbname = 'task_grid';

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

?>