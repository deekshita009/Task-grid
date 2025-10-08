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

    //This for filter HOD
    case 'hodnames':
        hodfilter($conn);
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
        $sql = "SELECT * FROM tasks ORDER BY task_id";
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

        $sql = "DELETE from tasks WHERE task_id=?";
        $check = $conn->prepare($sql);
        $check->bind_param('i', $_POST['id']);
        if ($check->execute()) {
            echo json_encode(['success' => true, 'message' => 'successfully Deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Not Deleted']);
        }
    } catch (exception $e) {
        echo json_encode(['success' => false, 'message' => 'Exception ']);
    }

}

//HOD Filter backend COde

function hodfilter($conn)
{
    try {
        $sql = "SELECT user_id, name FROM users WHERE role='HOD'";
        $check = $conn->query($sql);
        $hods = [];
        if ($check->num_rows > 0) {
            while ($row = $check->fetch_assoc()) {
                $hods[] = $row;
            }
        }
        echo json_encode(['success' => true, 'data' => $hods, 'message' => 'Fetched Successfully']);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Not Fetched: ' . $e->getMessage()]);
    }
}

//Faculty filter code
function facultyfilter($conn)
{
    $hod_id = intval($_POST['hod_id']);
    $sql = "SELECT ddept from users where user_id=?";
    $check = $conn->prepare($sql);
    $check->bind_param('i', $hod_id);
    $check->execute();
    $st = $check->get_result();
    if ($st->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'No data']);
        return;
    }
    $fet = $st->fetch_assoc();
    $dept = $fet['ddept'];

    // fetch faculty details
    $sql = "SELECT user_id, name FROM users WHERE ddept=? AND role='FACULTY'"; 
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



?>