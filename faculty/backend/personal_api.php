<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'FACULTY') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];

// Allow cross-origin for local testing
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// DB Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "college_erp";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    /* -------------------------------------------------
       FETCH — Get all tasks belonging to the logged user
    -------------------------------------------------- */
    case 'fetch':
        $stmt = $conn->prepare("SELECT * FROM personal WHERE user_id = ? ORDER BY start_date ASC");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($tasks);
        break;

    /* -------------------------------------------------
       INSERT — Add a new personal task
    -------------------------------------------------- */
    case 'insert':
        $input = json_decode(file_get_contents("php://input"), true);

        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $start = $input['start_date'] ?? '';
        $deadline = $input['deadline'] ?? '';
        $priority = $input['priority'] ?? 'Low';
        $status = $input['status'] ?? 'Pending';

        $stmt = $conn->prepare("INSERT INTO personal (title, description, start_date, deadline, priority, status, user_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $description, $start, $deadline, $priority, $status, $user_id);
        echo $stmt->execute() ? "success" : "error";
        break;

    /* -------------------------------------------------
       UPDATE — Update task (edit from modal)
    -------------------------------------------------- */
    case 'update':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = $input['id'] ?? '';
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $start = $input['start_date'] ?? '';
        $deadline = $input['deadline'] ?? '';
        $priority = $input['priority'] ?? '';
        $status = $input['status'] ?? 'Pending';

        $stmt = $conn->prepare("UPDATE personal SET title=?, description=?, start_date=?, deadline=?, priority=?, status=? 
                                WHERE id=? AND user_id=?");
        $stmt->bind_param("ssssssis", $title, $description, $start, $deadline, $priority, $status, $id, $user_id);
        echo $stmt->execute() ? "success" : "error";
        break;

    /* -------------------------------------------------
       COMPLETE — Mark task as Completed
    -------------------------------------------------- */
    case 'complete':
        $id = $_POST['id'] ?? '';
        $status = "Completed";
        $stmt = $conn->prepare("UPDATE personal SET status=? WHERE id=? AND user_id=?");
        $stmt->bind_param("sis", $status, $id, $user_id);
        echo $stmt->execute() ? "success" : "error";
        break;

    /* -------------------------------------------------
       DELETE — Remove task
    -------------------------------------------------- */
    case 'delete':
        $id = $_POST['id'] ?? '';
        $stmt = $conn->prepare("DELETE FROM personal WHERE id=? AND user_id=?");
        $stmt->bind_param("is", $id, $user_id);
        echo $stmt->execute() ? "success" : "error";
        break;

    /* -------------------------------------------------
       DEFAULT — Invalid action
    -------------------------------------------------- */
    default:
        echo "Invalid action";
        break;
}

$conn->close();
?>
