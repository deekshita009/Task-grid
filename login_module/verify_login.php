
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);



session_start();
include(__DIR__ . '/../shared/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['Userid'] ?? '';
    $password = $_POST['pass'] ?? '';
    $roleType = strtolower($_POST['type'] ?? '');
    $selected_dept = $_POST['selected_dept'] ?? null;

    $stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ? AND password = ?");
    $stmt->bind_param("ss", $user_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = strtoupper($user['role']);
        $_SESSION['dept'] = $selected_dept ?? $user['dept'];

        switch ($_SESSION['role']) {
            case 'HOD':
                header("Location: ../hod/index.php");
                break;
            case 'FACULTY':
                header("Location: ../faculty/index.php");
                break;
            case 'PRINCIPAL':
                header("Location: ../principal/index.php");
                break;
            case 'PORTFOLIO_HEAD':
                header("Location: ../portfolio_head/index.php");
                break;
            default:
                echo "<script>alert('Unknown role'); window.location.href='login.php';</script>";
        }
        exit;
    } else {
        echo "<script>alert('Invalid credentials'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
